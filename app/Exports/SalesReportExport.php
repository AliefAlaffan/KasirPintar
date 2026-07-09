<?php

namespace App\Exports;

use App\Services\ReportService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesReportExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(protected string $from, protected string $to)
    {
    }

    public function collection()
    {
        return app(ReportService::class)->getSalesReport($this->from, $this->to);
    }

    public function headings(): array
    {
        return ['No. Invoice', 'Tanggal', 'Kasir', 'Subtotal', 'Diskon', 'Total', 'Metode Bayar'];
    }

    public function map($transaction): array
    {
        return [
            $transaction->invoice_number,
            $transaction->created_at->format('d/m/Y H:i'),
            $transaction->user->name,
            $transaction->subtotal,
            $transaction->discount,
            $transaction->total,
            strtoupper($transaction->payment_method),
        ];
    }
}