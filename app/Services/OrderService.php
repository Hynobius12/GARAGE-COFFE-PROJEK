<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\InventoryService;

class OrderService
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function createOrder($data, $cashierId)
    {
        return DB::transaction(function () use ($data, $cashierId) {
            $orderNumber = 'GC-' . date('Ymd') . '-' . strtoupper(Str::random(4));
            
            $order = Order::create([
                'order_number' => $orderNumber,
                'cashier_id' => $cashierId,
                'status' => 'pending',
                'payment_method' => $data['payment_method'],
                'subtotal' => $data['subtotal'],
                'discount' => $data['discount'] ?? 0,
                'tax' => $data['tax'] ?? 0,
                'total_amount' => $data['total_amount'],
                'customer_name' => $data['customer_name'] ?? null,
                'notes' => $data['notes'] ?? null,
                'paid_at' => now(), 
            ]);

            foreach ($data['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_variant_id' => $item['product_variant_id'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['quantity'] * $item['unit_price'],
                    'special_instructions' => $item['special_instructions'] ?? null,
                ]);
            }

            $order->load('items');
            $this->inventoryService->deductStockForOrderItems($order);

            return $order;
        });
    }
}
