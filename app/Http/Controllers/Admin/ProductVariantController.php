<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function index(Product $product)
    {
        $product->load('variants');
        // return view('admin.products.variants', compact('product'));
        return view('admin.products.variants', compact('product'));
    }

    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'additional_price' => 'required|numeric|min:0',
            'is_available' => 'boolean'
        ]);

        $validated['is_available'] = $request->has('is_available');
        $product->variants()->create($validated);
        
        return back()->with('success', 'Varian berhasil ditambahkan.');
    }

    public function destroy(Product $product, ProductVariant $variant)
    {
        $variant->delete();
        return back()->with('success', 'Varian berhasil dihapus.');
    }
}
