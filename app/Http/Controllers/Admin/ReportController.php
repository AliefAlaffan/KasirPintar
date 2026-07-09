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

    public function index(Request $request)
    {
        $from = $request->from ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $to = $request->to ?? Carbon::now()->format('Y-m-d');

        $summary = $this->service->getSummary($from, $to);
        $dailyTrend = $this->service->getDailyTrend($from, $to);
        $bestSelling = $this->service->getBestSellingProducts($from, $to);
        $cashierPerformance = $this->service->getCashierPerformance($from, $to);
        $transactions = $this->service->getSalesReport($from, $to);

        return view('admin.reports.index', compact(
            'summary', 'dailyTrend', 'bestSelling', 'cashierPerformance', 'transactions', 'from', 'to'
        ));
    }

    public function exportPdf(Request $request)
    {
        $from = $request->from ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $to = $request->to ?? Carbon::now()->format('Y-m-d');

        $summary = $this->service->getSummary($from, $to);
        $transactions = $this->service->getSalesReport($from, $to);
        $bestSelling = $this->service->getBestSellingProducts($from, $to);

        $pdf = Pdf::loadView('admin.reports.pdf', compact('summary', 'transactions', 'bestSelling', 'from', 'to'));

        return $pdf->download("laporan-penjualan-{$from}-sd-{$to}.pdf");
    }

    public function exportExcel(Request $request)
    {
        $from = $request->from ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $to = $request->to ?? Carbon::now()->format('Y-m-d');

        return Excel::download(new SalesReportExport($from, $to), "laporan-penjualan-{$from}-sd-{$to}.xlsx");
    }
}