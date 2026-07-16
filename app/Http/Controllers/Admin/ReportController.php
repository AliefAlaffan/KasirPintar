<?php

namespace App\Http\Controllers\Admin;

use App\Exports\SalesReportExport;
use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

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

    public function index(Request $request)
    {
        [$from, $to] = $this->range($request);

        $summary = $this->service->getSummary($from, $to);
        $dailyTrend = $this->service->getDailyTrend($from, $to);

        return view('admin.reports.index', compact('summary', 'dailyTrend', 'from', 'to'));
    }

    public function products(Request $request)
    {
        [$from, $to] = $this->range($request);

        $bestSelling = $this->service->getBestSellingProducts($from, $to, 20);

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

        return Excel::download(new SalesReportExport($from, $to), "laporan-penjualan-{$from}-sd-{$to}.xlsx");
    }
}