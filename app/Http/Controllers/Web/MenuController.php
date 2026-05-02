<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\QrisOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        $products   = Product::with('variants')->where('is_available', true)->get();

        return view('public.menu', compact('categories', 'products'));
    }

    /**
     * Halaman QRIS publik
     */
    public function qrisPage()
    {
        return view('public.qris');
    }

    /**
     * Submit order + upload bukti dari E-Menu publik
     */
    public function submitOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_name'  => 'required|string|max:100',
            'customer_phone' => 'nullable|string|max:20',
            'customer_table' => 'nullable|string|max:50',
            'items'          => 'required|json',
            'total_amount'   => 'required|numeric|min:1000',
            'payment_proof'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'notes'          => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $items = json_decode($request->items, true);

        $qrisOrder = QrisOrder::create([
            'order_code'     => QrisOrder::generateOrderCode(),
            'customer_name'  => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_table' => $request->customer_table,
            'items'          => $items,
            'total_amount'   => $request->total_amount,
            'notes'          => $request->notes,
            'status'         => 'pending_payment',
        ]);

        // Jika langsung upload bukti
        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('qris-proofs', 'public');
            $qrisOrder->update([
                'payment_proof' => $path,
                'status'        => 'payment_uploaded',
            ]);
        }

        return response()->json([
            'success'    => true,
            'order_code' => $qrisOrder->order_code,
            'order_id'   => $qrisOrder->id,
        ]);
    }

    /**
     * Upload bukti setelah order dibuat
     */
    public function uploadProof(Request $request, QrisOrder $qrisOrder)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($qrisOrder->payment_proof) {
            Storage::disk('public')->delete($qrisOrder->payment_proof);
        }

        $path = $request->file('payment_proof')->store('qris-proofs', 'public');
        $qrisOrder->update([
            'payment_proof' => $path,
            'status'        => 'payment_uploaded',
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Cek status order
     */
    public function orderStatus(string $orderCode)
    {
        $order = QrisOrder::where('order_code', $orderCode)->firstOrFail();
        return view('public.order-status', compact('order'));
    }
}
