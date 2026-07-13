<?php

namespace App\Http\Controllers\Manajer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Restock;
use App\Models\StockOpname;

class DashboardController extends Controller
{
    public function index()
    {
        $lowStock = Product::whereColumn('stock', '<=', 'min_stock')
            ->where('is_active', true)
            ->orderBy('stock', 'asc')
            ->limit(8)
            ->get();

        $recentRestocks = Restock::with('supplier')
            ->latest()
            ->limit(5)
            ->get();

        $pendingOpnames = StockOpname::where('status', 'draft')
            ->latest()
            ->limit(5)
            ->get();

        $summary = [
            'low_stock_count'      => Product::whereColumn('stock', '<=', 'min_stock')->where('is_active', true)->count(),
            'restock_this_month'   => Restock::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'pending_opname_count' => StockOpname::where('status', 'draft')->count(),
        ];

        return view('manajer.dashboard', compact('lowStock', 'recentRestocks', 'pendingOpnames', 'summary'));
    }
}