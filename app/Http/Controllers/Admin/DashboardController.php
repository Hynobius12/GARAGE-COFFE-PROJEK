<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\QrisOrder;
use App\Models\RawMaterial;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Statistik hari ini (POS + Web orders)
        $todayRevenuePOS   = Order::whereDate('created_at', $today)->where('status', 'completed')->sum('total_amount');
        $todayRevenueWeb   = QrisOrder::whereDate('created_at', $today)->where('status', 'completed')->sum('total_amount');
        $todayRevenue      = $todayRevenuePOS + $todayRevenueWeb;

        $todayOrdersPOS    = Order::whereDate('created_at', $today)->count();
        $todayOrdersWeb    = QrisOrder::whereDate('created_at', $today)->count();
        $todayOrders       = $todayOrdersPOS + $todayOrdersWeb;

        $todayItems    = DB::table('order_items')
                            ->join('orders', 'order_items.order_id', '=', 'orders.id')
                            ->whereDate('orders.created_at', $today)
                            ->sum('order_items.quantity');

        // QRIS pending dari e-menu publik
        $qrisPending   = QrisOrder::where('status', 'payment_uploaded')->count();
        $qrisUnpaid    = QrisOrder::where('status', 'pending_payment')->count();

        // Stok kritis (raw material < 10 unit)
        $lowStock      = RawMaterial::where('current_stock', '<', 10)->count();

        // Chart 7 hari: revenue per hari
        $chartData = Order::where('status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as revenue, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $chartLabels  = [];
        $chartRevenue = [];
        $chartOrders  = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = Carbon::now()->subDays($i)->toDateString();
            $chartLabels[]  = Carbon::parse($d)->format('d M');
            $chartRevenue[] = $chartData[$d]->revenue ?? 0;
            $chartOrders[]  = $chartData[$d]->count   ?? 0;
        }

        // Pesanan terbaru
        $recentOrders = Order::with('cashier')->latest()->take(8)->get();

        // QRIS orders terbaru perlu konfirmasi
        $pendingQrisOrders = QrisOrder::where('status', 'payment_uploaded')
                                ->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'todayRevenue', 'todayOrders', 'todayItems',
            'qrisPending', 'qrisUnpaid', 'lowStock',
            'chartLabels', 'chartRevenue', 'chartOrders',
            'recentOrders', 'pendingQrisOrders'
        ));
    }
}

