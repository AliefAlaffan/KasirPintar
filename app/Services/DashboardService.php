<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getSummary(): array
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();

        return [
            'revenue_today'   => Transaction::whereDate('created_at', $today)->sum('total'),
            'revenue_month'   => Transaction::where('created_at', '>=', $startOfMonth)->sum('total'),
            'profit_month'    => $this->calculateProfit($startOfMonth),
            'transactions_today' => Transaction::whereDate('created_at', $today)->count(),
        ];
    }

    protected function calculateProfit(Carbon $from): float
    {
        // Laba = (harga jual - harga modal) x qty, dihitung dari transaction_details
        return (float) DB::table('transaction_details')
            ->join('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->join('products', 'products.id', '=', 'transaction_details.product_id')
            ->where('transactions.created_at', '>=', $from)
            ->selectRaw('SUM((transaction_details.price - products.cost_price) * transaction_details.quantity) as profit')
            ->value('profit') ?? 0;
    }

    public function getWeeklySalesTrend(): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $data[] = [
                'date'  => $date->format('d M'),
                'total' => (float) Transaction::whereDate('created_at', $date)->sum('total'),
            ];
        }
        return $data;
    }

    public function getLowStockProducts()
    {
        return Product::whereColumn('stock', '<=', 'min_stock')
            ->where('is_active', true)
            ->orderBy('stock', 'asc')
            ->limit(10)
            ->get();
    }

    public function getRecentActivities()
    {
        return ActivityLog::with('user')
            ->latest()
            ->limit(8)
            ->get();
    }
}