<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\CashClosure;
use App\Models\Transaction;
use Carbon\Carbon;

class CashClosureService
{
    /**
     * Tentukan awal periode: sejak tutup kasir TERAKHIR milik kasir ini,
     * atau kalau belum pernah tutup kasir sama sekali, sejak awal hari ini.
     */
    protected function getPeriodStart(int $userId): Carbon
    {
        $lastClosure = CashClosure::where('user_id', $userId)->latest('period_end')->first();

        return $lastClosure
            ? Carbon::parse($lastClosure->period_end)
            : Carbon::today();
    }

    public function getCurrentSummary(int $userId): array
    {
        $periodStart = $this->getPeriodStart($userId);
        $periodEnd = now();

        $transactions = Transaction::where('user_id', $userId)
            ->whereBetween('created_at', [$periodStart, $periodEnd])
            ->get();

        return [
            'period_start'       => $periodStart,
            'period_end'         => $periodEnd,
            'transaction_count'  => $transactions->count(),
            'system_cash_total'  => (float) $transactions->where('payment_method', 'cash')->sum('total'),
            'system_qris_total'  => (float) $transactions->where('payment_method', 'qris')->sum('total'),
            'system_debit_total' => (float) $transactions->where('payment_method', 'debit')->sum('total'),
        ];
    }

    public function store(int $userId, float $physicalCash, ?string $note = null): CashClosure
    {
        $summary = $this->getCurrentSummary($userId);

        $closure = CashClosure::create([
            'user_id'            => $userId,
            'period_start'       => $summary['period_start'],
            'period_end'         => $summary['period_end'],
            'transaction_count'  => $summary['transaction_count'],
            'system_cash_total'  => $summary['system_cash_total'],
            'system_qris_total'  => $summary['system_qris_total'],
            'system_debit_total' => $summary['system_debit_total'],
            'physical_cash'      => $physicalCash,
            'difference'         => $physicalCash - $summary['system_cash_total'],
            'note'               => $note,
        ]);

        ActivityLog::create([
            'user_id'     => $userId,
            'action'      => 'Tutup kasir',
            'description' => 'Selisih kas: Rp ' . number_format($closure->difference, 0, ',', '.'),
        ]);

        return $closure;
    }

    public function history(int $userId, int $perPage = 15)
    {
        return CashClosure::where('user_id', $userId)
            ->latest('period_end')
            ->paginate($perPage);
    }
}