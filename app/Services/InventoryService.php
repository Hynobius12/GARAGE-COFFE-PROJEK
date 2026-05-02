<?php

namespace App\Services;

use App\Models\ProductRecipe;
use App\Models\RawMaterial;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\DB;
use Exception;

class InventoryService
{
    public function deductStockForOrderItems($order)
    {
        foreach ($order->items as $item) {
            $recipes = ProductRecipe::where('product_id', $item->product_id)
                ->where(function($query) use ($item) {
                    $query->where('product_variant_id', $item->product_variant_id)
                          ->orWhereNull('product_variant_id');
                })->get();
                
            foreach ($recipes as $recipe) {
                $totalNeeded = $recipe->quantity_needed * $item->quantity;
                $material = $recipe->rawMaterial;
                
                $material->decrement('current_stock', $totalNeeded);

                StockTransaction::create([
                    'raw_material_id' => $material->id,
                    'type' => 'out',
                    'quantity' => $totalNeeded,
                    'reference_type' => 'order',
                    'reference_id' => $order->id,
                    'notes' => 'Penjualan via POS menu: ' . $order->order_number,
                    'created_by' => $order->cashier_id
                ]);
            }
        }
    }
}
