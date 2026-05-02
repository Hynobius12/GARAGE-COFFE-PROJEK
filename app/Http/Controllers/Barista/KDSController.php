<?php

namespace App\Http\Controllers\Barista;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class KDSController extends Controller
{
    public function index()
    {
        $orders = Order::with('items.product')->whereIn('status', ['pending', 'processing'])->get();
        $qrisOrders = \App\Models\QrisOrder::whereIn('status', ['confirmed', 'processing'])->get();

        $unifiedOrders = collect();

        foreach ($orders as $o) {
            $unifiedOrders->push([
                'id' => $o->id,
                'order_number' => $o->order_number,
                'customer_name' => $o->customer_name,
                'type' => 'pos',
                'status' => $o->status,
                'created_at' => $o->created_at,
                'items' => $o->items->map(function($i) {
                    return [
                        'name' => $i->product->name . ($i->variant_name ? ' ('.$i->variant_name.')' : ''),
                        'quantity' => $i->quantity,
                        'notes' => $i->special_instructions,
                    ];
                })
            ]);
        }

        foreach ($qrisOrders as $q) {
            $items = is_array($q->items) ? $q->items : json_decode($q->items, true);
            $unifiedOrders->push([
                'id' => $q->id,
                'order_number' => $q->order_code,
                'customer_name' => $q->customer_name,
                'type' => 'web',
                'status' => $q->status === 'confirmed' ? 'pending' : 'processing',
                'created_at' => $q->created_at,
                'items' => collect($items)->map(function($i) {
                    return [
                        'name' => $i['name'] . (isset($i['variant_name']) && $i['variant_name'] ? ' ('.$i['variant_name'].')' : ''),
                        'quantity' => $i['quantity'],
                        'notes' => $i['special_instructions'] ?? '',
                    ];
                })
            ]);
        }

        $orders = $unifiedOrders->sortBy('created_at')->values();

        return view('barista.kds', compact('orders'));
    }

    public function process(Order $order)
    {
        $order->update(['status' => 'processing']);
        return back()->with('success', 'Pesanan sedang diproses.');
    }

    public function complete(Order $order)
    {
        $order->update(['status' => 'completed']);
        return back()->with('success', 'Pesanan selesai.');
    }
}
