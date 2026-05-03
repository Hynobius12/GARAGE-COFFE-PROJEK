<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'category_id' => 'required|exists:categories,id',
    //         'name' => 'required|string|max:255',
    //         'description' => 'nullable|string',
    //         'base_price' => 'required|numeric|min:0',
    //         'is_available' => 'boolean',
    //         'is_featured' => 'boolean',
    //         'allergen_info' => 'nullable|string',
    //         'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
    //     ]);

    //     $validated['slug'] = Str::slug($validated['name']) . '-' . time();
    //     $validated['is_available'] = $request->has('is_available');
    //     $validated['is_featured'] = $request->has('is_featured');

    //     if ($request->hasFile('image_file')) {
    //         $validated['image'] = $request->file('image_file')->store('products', 'public');
    //     }

    //     Product::create($validated);
    //     return redirect()->route('admin.products.index')->with('success', 'Produk ditambahkan!');
    // }


    public function store(Request $request)
    {
        // Hapus dd($request->all()) kalau sudah yakin datanya masuk

        $product = new \App\Models\Product();
        $product->category_id = $request->category_id;
        $product->name = $request->name;
        $product->slug = \Illuminate\Support\Str::slug($request->name);
        $product->description = $request->description;

        // UBAH BAGIAN INI: samakan dengan "base_price" di dd tadi
        $product->base_price = $request->base_price;

        $product->is_available = $request->has('is_available');

        // Tambahan buat allergen (karena di dd kamu ada ini)
        $product->allergen_info = $request->allergen_info;

        if ($request->hasFile('image_file')) {
            $product->image = $request->file('image_file')->store('products', 'public');
        }

        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambah!');
    }
    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    // public function update(Request $request, Product $product)
    // {
    //     $validated = $request->validate([
    //         'category_id' => 'required|exists:categories,id',
    //         'name' => 'required|string|max:255',
    //         'description' => 'nullable|string',
    //         'base_price' => 'required|numeric|min:0',
    //         'is_available' => 'boolean',
    //         'is_featured' => 'boolean',
    //         'allergen_info' => 'nullable|string',
    //         'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
    //     ]);

    //     $validated['is_available'] = $request->has('is_available');
    //     $validated['is_featured'] = $request->has('is_featured');

    //     if ($request->hasFile('image_file')) {
    //         if ($product->image) {
    //             Storage::disk('public')->delete($product->image);
    //         }
    //         $validated['image'] = $request->file('image_file')->store('products', 'public');
    //     }

    //     $product->update($validated);
    //     return redirect()->route('admin.products.index')->with('success', 'Produk diperbarui!');
    // }


    public function update(Request $request, Product $product)
    {
        // 1. Validasi
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric', // Pastikan divalidasi sesuai name di form
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        // 2. Update Data secara Manual
        $product->category_id = $request->category_id;
        $product->name = $request->name;

        // Update slug jika nama berubah
        $product->slug = \Illuminate\Support\Str::slug($request->name);

        $product->description = $request->description;
        $product->base_price = $request->base_price; // Pakai base_price sesuai kolom DB

        // $product->is_active = $request->has('is_active');
        $product->description = $request->description;
        $product->base_price = $request->base_price;
        $product->is_available = $request->has('is_available');

        // 3. Logika Update Foto
        if ($request->hasFile('image_file')) {
            // Hapus foto lama agar penyimpanan tidak penuh
            if ($product->image) {
                \Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('image_file')->store('products', 'public');
        }

        // 4. Simpan Perubahan
        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui!');
    }
    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produk dihapus!');
    }
}
