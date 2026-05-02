<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\RawMaterial;
use App\Models\ProductRecipe;
use Illuminate\Http\Request;

class ProductRecipeController extends Controller
{
    public function index(Product $product)
    {
        $product->load('recipes.rawMaterial');
        $rawMaterials = RawMaterial::orderBy('name')->get();
        return view('admin.products.recipes', compact('product', 'rawMaterials'));
    }

    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'raw_material_id' => 'required|exists:raw_materials,id',
            'quantity_needed' => 'required|numeric|min:0.01'
        ]);

        $validated['product_id'] = $product->id;
        
        ProductRecipe::create($validated);
        
        return back()->with('success', 'Bahan baku ditambahkan ke resep.');
    }

    public function destroy(Product $product, ProductRecipe $recipe)
    {
        $recipe->delete();
        return back()->with('success', 'Bahan dihapus dari resep.');
    }
}
