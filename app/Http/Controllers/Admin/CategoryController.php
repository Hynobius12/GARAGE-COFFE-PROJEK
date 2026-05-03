<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('sort_order')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'description' => 'nullable|string',
    //         'is_active' => 'boolean',
    //         'sort_order' => 'integer',
    //         'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
    //     ]);

    //     $validated['slug'] = Str::slug($validated['name']) . '-' . time();
    //     $validated['is_active'] = $request->has('is_active');

    //     if ($request->hasFile('image_file')) {
    //         $path = $request->file('image_file')->store('categories', 'public');
    //         $validated['image'] = $path;
    //     }

    //     Category::create($validated);
    //     return redirect()->route('admin.categories.index')->with('success', 'Kategori ditambahkan!');
    // }


public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'sort_order' => 'integer',
        'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
    ]);

    $category = new \App\Models\Category();
    $category->name = $request->name;
    
    // TAMBAHKAN INI: Bikin slug otomatis dari nama
    $category->slug = \Illuminate\Support\Str::slug($request->name); 
    
    $category->description = $request->description;
    $category->sort_order = $request->sort_order ?? 0;
    $category->is_active = $request->has('is_active');

    if ($request->hasFile('image_file')) {
        $category->image = $request->file('image_file')->store('categories', 'public');
    }

    $category->save();

    return redirect()->route('admin.categories.index')->with('success', 'Kategori baru berhasil ditambahkan!');
}
    public function show(Category $category)
    {
        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    // public function update(Request $request, Category $category)
    // {
    // $validated = $request->validate([
    //     'name' => 'required|string|max:255',
    //     'description' => 'nullable|string',
    //     'is_active' => 'boolean',
    //     'sort_order' => 'integer',
    //     'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
    // ]);

    // if ($request->hasFile('image_file')) {
    //     if ($category->image) {
    //         Storage::disk('public')->delete($category->image);
    //     }
    //     $validated['image'] = $request->file('image_file')->store('categories', 'public');
    // }

    // $validated['is_active'] = $request->has('is_active');
    // $category->update($validated);

    // return redirect()->route('admin.categories.index')->with('success', 'Kategori diperbarui!');
    // }
    public function update(Request $request, Category $category)
    {
        // 1. Validasi input dari form
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'integer',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        // 2. Isi data satu per satu ke objek kategori
        $category->name = $request->name;
        $category->description = $request->description;
        $category->sort_order = $request->sort_order;
        $category->is_active = $request->has('is_active'); // Checkbox butuh perlakuan khusus

        // 3. Logika Upload Foto
        if ($request->hasFile('image_file')) {
            // Hapus foto lama di folder storage agar tidak menumpuk
            if ($category->image) {
                \Storage::disk('public')->delete($category->image);
            }
            // Simpan foto baru ke folder 'categories' di disk 'public'
            $category->image = $request->file('image_file')->store('categories', 'public');
        }

        // 4. Simpan semua perubahan ke database
        $category->save();

        // 5. Kembali ke halaman index dengan pesan sukses
        return redirect()->route('admin.categories.index')->with('success', 'Kategori diperbarui!');
    }


    public function destroy(Category $category)
    {
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Kategori dihapus!');
    }
}
