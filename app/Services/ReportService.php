<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function getSalesReport(string $from, string $to)
    {
        $from = Carbon::parse($from)->startOfDay();
        $to = Carbon::parse($to)->endOfDay();

        return Transaction::with('user')
            ->whereBetween('created_at', [$from, $to])
            ->latest()
            ->get();
    }

    public function getSummary(string $from, string $to): array
    {
        $from = Carbon::parse($from)->startOfDay();
        $to = Carbon::parse($to)->endOfDay();

        $totalRevenue = Transaction::whereBetween('created_at', [$from, $to])->sum('total');
        $totalTransactions = Transaction::whereBetween('created_at', [$from, $to])->count();

        $totalCost = DB::table('transaction_details')
            ->join('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->join('products', 'products.id', '=', 'transaction_details.product_id')
            ->whereBetween('transactions.created_at', [$from, $to])
            ->selectRaw('SUM(products.cost_price * transaction_details.quantity) as total_cost')
            ->value('total_cost') ?? 0;

        $totalProfit = $totalRevenue - $totalCost;

        return [
            'total_revenue'      => (float) $totalRevenue,
            'total_cost'         => (float) $totalCost,
            'total_profit'       => (float) $totalProfit,
            'total_transactions' => $totalTransactions,
            'avg_transaction'    => $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0,
        ];
    }

    public function getDailyTrend(string $from, string $to)
    {
        $from = Carbon::parse($from)->startOfDay();
        $to = Carbon::parse($to)->endOfDay();

        return Transaction::whereBetween('created_at', [$from, $to])
            ->selectRaw('DATE(created_at) as date, SUM(total) as revenue, COUNT(*) as transactions')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    public function getBestSellingProducts(string $from, string $to, int $limit = 10)
    {
        $from = Carbon::parse($from)->startOfDay();
        $to = Carbon::parse($to)->endOfDay();

        return DB::table('transaction_details')
            ->join('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->join('products', 'products.id', '=', 'transaction_details.product_id')
            ->whereBetween('transactions.created_at', [$from, $to])
            ->select(
                'products.id',
                'products.name',
                'products.sku',
                DB::raw('SUM(transaction_details.quantity) as total_sold'),
                DB::raw('SUM(transaction_details.subtotal) as total_revenue')
            )
            ->groupBy('products.id', 'products.name', 'products.sku')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get();
    }

    public function getCashierPerformance(string $from, string $to)
    {
        $from = Carbon::parse($from)->startOfDay();
        $to = Carbon::parse($to)->endOfDay();

        return Transaction::whereBetween('transactions.created_at', [$from, $to])
            ->join('users', 'users.id', '=', 'transactions.user_id')
            ->select('users.name', DB::raw('COUNT(*) as total_transactions'), DB::raw('SUM(transactions.total) as total_revenue'))
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_revenue')
            ->get();
    }
}