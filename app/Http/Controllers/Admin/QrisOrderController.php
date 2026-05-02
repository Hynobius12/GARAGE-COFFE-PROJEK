<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QrisOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QrisOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = QrisOrder::latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('order_code', 'like', '%'.$request->search.'%')
                  ->orWhere('customer_name', 'like', '%'.$request->search.'%');
            });
        }

        $orders = $query->paginate(20)->appends($request->query());
        return view('admin.qris-orders.index', compact('orders'));
    }

    public function show(QrisOrder $qrisOrder)
    {
        return view('admin.qris-orders.show', compact('qrisOrder'));
    }

    public function confirm(QrisOrder $qrisOrder)
    {
        $qrisOrder->update([
            'status'       => 'confirmed',
            'confirmed_at' => now(),
            'confirmed_by' => auth()->id(),
        ]);
        return back()->with('success', 'Pembayaran #'.$qrisOrder->order_code.' berhasil dikonfirmasi!');
    }

    public function process(QrisOrder $qrisOrder)
    {
        $qrisOrder->update(['status' => 'processing']);
        return back()->with('success', 'Pesanan #'.$qrisOrder->order_code.' sedang diproses.');
    }

    public function complete(QrisOrder $qrisOrder)
    {
        $qrisOrder->update(['status' => 'completed']);
        return back()->with('success', 'Pesanan #'.$qrisOrder->order_code.' selesai.');
    }

    public function reject(QrisOrder $qrisOrder)
    {
        $qrisOrder->update(['status' => 'cancelled']);
        return back()->with('error', 'Pesanan #'.$qrisOrder->order_code.' ditolak.');
    }
}
