<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class POSController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        $products = Product::with('variants')->where('is_available', true)->get();
        return view('cashier.pos', compact('categories', 'products'));
    }

    public function checkout(Request $request, OrderService $orderService)
    {
        $data = $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.product_variant_id' => 'nullable|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.special_instructions' => 'nullable|string',
            'payment_method' => 'required|in:cash,qris,transfer',
            'customer_name' => 'nullable|string',
            'notes' => 'nullable|string',
            'subtotal' => 'required|numeric',
            'total_amount' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'tax' => 'nullable|numeric',
        ]);

        try {
            $order = $orderService->createOrder($data, Auth::id());
            return response()->json([
                'success' => true, 
                'message' => 'Pesanan berhasil dibuat!', 
                'order' => $order
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Gagal membuat pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function history()
    {
        $orders = \App\Models\Order::with(['items.product', 'items.variant'])
            ->whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get();
            
        $qrisOrders = \App\Models\QrisOrder::whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get();

        $unifiedOrders = collect();

        foreach ($orders as $o) {
            $unifiedOrders->push([
                'id' => $o->id,
                'order_number' => $o->order_number,
                'customer_name' => $o->customer_name,
                'type' => 'pos',
                'status' => $o->status,
                'payment_method' => $o->payment_method,
                'total_amount' => $o->total_amount,
                'created_at' => $o->created_at,
            ]);
        }

        foreach ($qrisOrders as $q) {
            $unifiedOrders->push([
                'id' => $q->id,
                'order_number' => $q->order_code,
                'customer_name' => $q->customer_name,
                'customer_table' => $q->customer_table,
                'type' => 'web',
                'status' => $q->status,
                'payment_method' => 'qris',
                'total_amount' => $q->total_amount,
                'payment_proof_url' => $q->payment_proof ? \Illuminate\Support\Facades\Storage::url($q->payment_proof) : null,
                'created_at' => $q->created_at,
            ]);
        }

        $ordersList = $unifiedOrders->sortByDesc('created_at')->take(50)->values();
            
        return response()->json(['success' => true, 'orders' => $ordersList]);
    }

    public function updateStatus(Request $request, string $type, int $id)
    {
        $request->validate(['action' => 'required|in:process,complete,confirm,cancel']);
        $action = $request->action;

        if ($type === 'pos') {
            $order = \App\Models\Order::findOrFail($id);
            $map = [
                'process'  => 'processing',
                'complete' => 'completed',
                'cancel'   => 'cancelled',
            ];
            if (!isset($map[$action])) {
                return response()->json(['success' => false, 'message' => 'Aksi tidak valid.'], 422);
            }
            $order->update(['status' => $map[$action]]);
            return response()->json(['success' => true, 'status' => $order->status]);
        }

        if ($type === 'web') {
            $order = \App\Models\QrisOrder::findOrFail($id);
            $map = [
                'confirm'  => 'confirmed',
                'process'  => 'processing',
                'complete' => 'completed',
                'cancel'   => 'cancelled',
            ];
            if (!isset($map[$action])) {
                return response()->json(['success' => false, 'message' => 'Aksi tidak valid.'], 422);
            }
            $order->update([
                'status' => $map[$action],
                'confirmed_by' => $action === 'confirm' ? Auth::id() : $order->confirmed_by,
            ]);
            return response()->json(['success' => true, 'status' => $order->status]);
        }

        return response()->json(['success' => false, 'message' => 'Tipe order tidak valid.'], 422);
    }
}
