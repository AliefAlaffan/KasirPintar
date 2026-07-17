<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function __construct(protected ReportService $service)
    {
    }

    protected function range(Request $request): array
    {
        return [
            $request->from ?? Carbon::now()->startOfMonth()->format('Y-m-d'),
            $request->to ?? Carbon::now()->format('Y-m-d'),
        ];
    }

    public function hub()
    {
        return view('admin.reports.hub');
    }

    public function sales(Request $request)
    {
        [$from, $to] = $this->range($request);

        $summary = $this->service->getSummary($from, $to);
        $dailyTrend = $this->service->getDailyTrend($from, $to);

        return view('admin.reports.sales', compact('summary', 'dailyTrend', 'from', 'to'));
    }

    public function products(Request $request)
    {
        [$from, $to] = $this->range($request);
        $bestSelling = $this->service->getBestSellingProducts($from, $to, 50);

        return view('admin.reports.products', compact('bestSelling', 'from', 'to'));
    }

    public function cashiers(Request $request)
    {
        [$from, $to] = $this->range($request);
        $cashierPerformance = $this->service->getCashierPerformance($from, $to);

        return view('admin.reports.cashiers', compact('cashierPerformance', 'from', 'to'));
    }

    public function transactions(Request $request)
    {
        [$from, $to] = $this->range($request);
        $transactions = $this->service->getSalesReport($from, $to);

        return view('admin.reports.transactions', compact('transactions', 'from', 'to'));
    }

    public function voided(Request $request)
    {
        [$from, $to] = $this->range($request);
        $voidedTransactions = $this->service->getVoidedTransactions($from, $to);

        return view('admin.reports.voided', compact('voidedTransactions', 'from', 'to'));
    }

    public function cashClosures(Request $request)
    {
        [$from, $to] = $this->range($request);
        $cashClosures = $this->service->getCashClosures($from, $to);

        return view('admin.reports.cash-closures', compact('cashClosures', 'from', 'to'));
    }

    public function exportPdf(Request $request)
    {
        [$from, $to] = $this->range($request);

        $summary = $this->service->getSummary($from, $to);
        $transactions = $this->service->getSalesReport($from, $to);
        $bestSelling = $this->service->getBestSellingProducts($from, $to);

        $pdf = Pdf::loadView('admin.reports.pdf', compact('summary', 'transactions', 'bestSelling', 'from', 'to'));

        return $pdf->download("laporan-penjualan-{$from}-sd-{$to}.pdf");
    }

    public function exportExcel(Request $request)
    {
        [$from, $to] = $this->range($request);

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\SalesReportExport($from, $to),
            "laporan-penjualan-{$from}-sd-{$to}.xlsx"
        );
    }

    protected function reportLabel(string $type): string
    {
        return match ($type) {
            'products'      => 'Produk Terlaris',
            'cashiers'      => 'Performa Kasir',
            'transactions'  => 'Detail Transaksi',
            'voided'        => 'Riwayat Void',
            'cash-closures' => 'Laporan Kas',
            default         => 'Laporan',
        };
    }

    protected function reportData(string $type, string $from, string $to): array
    {
        return match ($type) {
            'products' => [
                'headers' => ['Produk', 'SKU', 'Terjual', 'Pendapatan'],
                'rows' => $this->service->getBestSellingProducts($from, $to, 200)
                    ->map(fn ($i) => [$i->name, $i->sku, $i->total_sold, 'Rp ' . number_format($i->total_revenue, 0, ',', '.')])
                    ->toArray(),
            ],
            'cashiers' => [
                'headers' => ['Kasir', 'Jumlah Transaksi', 'Total Pendapatan'],
                'rows' => $this->service->getCashierPerformance($from, $to)
                    ->map(fn ($i) => [$i->name, $i->total_transactions, 'Rp ' . number_format($i->total_revenue, 0, ',', '.')])
                    ->toArray(),
            ],
            'transactions' => [
                'headers' => ['Invoice', 'Tanggal', 'Kasir', 'Total', 'Metode', 'Status'],
                'rows' => $this->service->getSalesReport($from, $to)
                    ->map(fn ($i) => [
                        $i->invoice_number, $i->created_at->format('d/m/Y H:i'), $i->user->name,
                        'Rp ' . number_format($i->total, 0, ',', '.'), strtoupper($i->payment_method),
                        $i->is_voided ? 'Void' : 'Berhasil',
                    ])->toArray(),
            ],
            'voided' => [
                'headers' => ['Invoice', 'Kasir', 'Total', 'Waktu Dibatalkan', 'Alasan'],
                'rows' => $this->service->getVoidedTransactions($from, $to)
                    ->map(fn ($i) => [
                        $i->invoice_number, $i->user->name, 'Rp ' . number_format($i->total, 0, ',', '.'),
                        $i->voided_at->format('d/m/Y H:i'), $i->void_reason,
                    ])->toArray(),
            ],
            'cash-closures' => [
                'headers' => ['Kasir', 'Waktu Lapor', 'Tunai Sistem', 'Uang Fisik', 'Selisih'],
                'rows' => $this->service->getCashClosures($from, $to)
                    ->map(fn ($i) => [
                        $i->user->name, $i->period_end->format('d/m/Y H:i'),
                        'Rp ' . number_format($i->system_cash_total, 0, ',', '.'),
                        'Rp ' . number_format($i->physical_cash, 0, ',', '.'),
                        'Rp ' . number_format($i->difference, 0, ',', '.'),
                    ])->toArray(),
            ],
            default => ['headers' => [], 'rows' => []],
        };
    }

    public function exportGenericPdf(Request $request, string $type)
    {
        [$from, $to] = $this->range($request);
        $data = $this->reportData($type, $from, $to);
        $label = $this->reportLabel($type);

        $pdf = Pdf::loadView('admin.reports.generic-pdf', [
            'label' => $label, 'from' => $from, 'to' => $to,
            'headers' => $data['headers'], 'rows' => $data['rows'],
        ]);

        return $pdf->download("laporan-{$type}-{$from}-sd-{$to}.pdf");
    }

    public function exportGenericCsv(Request $request, string $type)
    {
        [$from, $to] = $this->range($request);
        $data = $this->reportData($type, $from, $to);
        $filename = "laporan-{$type}-{$from}-sd-{$to}.csv";

        return response()->streamDownload(function () use ($data) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $data['headers']);
            foreach ($data['rows'] as $row) {
                fputcsv($out, $row);
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}