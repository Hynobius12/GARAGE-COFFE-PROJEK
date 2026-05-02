<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\QrisOrder;
use App\Exports\SalesExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->subDays(7)->format('Y-m-d'));
        $endDate   = $request->input('end_date', now()->format('Y-m-d'));

        // Chart: daily revenue (POS + Web)
        $chartPOS = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('date')->orderBy('date')->get()->keyBy('date');

        $chartWeb = QrisOrder::where('status', 'completed')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('date')->orderBy('date')->get()->keyBy('date');

        // Merge both per date
        $allDates = $chartPOS->keys()->merge($chartWeb->keys())->unique()->sort()->values();
        $chartLabels = $allDates;
        $chartValues = $allDates->map(fn($d) => ($chartPOS[$d]->total ?? 0) + ($chartWeb[$d]->total ?? 0));

        // POS orders list
        $posOrders = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->latest()->get()
            ->map(fn($o) => [
                'order_number'   => $o->order_number,
                'customer_name'  => $o->customer_name ?? '-',
                'payment_method' => $o->payment_method,
                'total_amount'   => $o->total_amount,
                'type'           => 'POS',
                'created_at'     => $o->created_at,
            ]);

        // Web orders list
        $webOrders = QrisOrder::where('status', 'completed')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->latest()->get()
            ->map(fn($q) => [
                'order_number'   => $q->order_code,
                'customer_name'  => $q->customer_name ?? '-',
                'payment_method' => 'QRIS (Web)',
                'total_amount'   => $q->total_amount,
                'type'           => 'Web',
                'created_at'     => $q->created_at,
            ]);

        $orders = $posOrders->merge($webOrders)->sortByDesc('created_at')->values();
        $totalRevenue = $orders->sum('total_amount');

        return view('admin.reports.index', compact('orders', 'startDate', 'endDate', 'chartLabels', 'chartValues', 'totalRevenue'));
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new SalesExport($request->start_date, $request->end_date), 'Laporan_Penjualan_' . date('Ymd') . '.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        $posOrders = Order::where('status', 'completed');
        $webOrders = QrisOrder::where('status', 'completed');
        if ($startDate && $endDate) {
            $posOrders->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            $webOrders->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        $orders = $posOrders->latest()->get()
            ->map(fn($o) => ['order_number' => $o->order_number, 'customer_name' => $o->customer_name ?? '-', 'payment_method' => $o->payment_method, 'total_amount' => $o->total_amount, 'type' => 'POS', 'created_at' => $o->created_at])
            ->merge(
                $webOrders->latest()->get()
                    ->map(fn($q) => ['order_number' => $q->order_code, 'customer_name' => $q->customer_name ?? '-', 'payment_method' => 'QRIS (Web)', 'total_amount' => $q->total_amount, 'type' => 'Web', 'created_at' => $q->created_at])
            )->sortByDesc('created_at')->values();

        $pdf = Pdf::loadView('admin.reports.pdf', compact('orders', 'startDate', 'endDate'));
        return $pdf->download('Laporan_Penjualan_' . date('Ymd') . '.pdf');
    }
}

