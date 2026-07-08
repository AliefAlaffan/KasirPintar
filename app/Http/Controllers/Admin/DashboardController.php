<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function __construct(protected DashboardService $dashboardService)
    {
    }

    public function index()
    {
        return view('admin.dashboard', [
            'summary'      => $this->dashboardService->getSummary(),
            'salesTrend'   => $this->dashboardService->getWeeklySalesTrend(),
            'lowStock'     => $this->dashboardService->getLowStockProducts(),
            'activities'   => $this->dashboardService->getRecentActivities(),
        ]);
    }
}