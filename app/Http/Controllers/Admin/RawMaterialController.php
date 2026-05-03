<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RawMaterial;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RawMaterialController extends Controller
{
    public function index()
    {
        $rawMaterials = RawMaterial::orderBy('name')->get();
        return view('admin.raw-materials.index', compact('rawMaterials'));
    }

    public function create()
    {
        return view('admin.raw-materials.create');
    }

    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'sku' => 'nullable|string|max:50|unique:raw_materials,sku',
    //         'unit' => 'required|string|max:50',
    //         'current_stock' => 'required|numeric|min:0',
    //         'reorder_level' => 'required|numeric|min:0',
    //     ]);

    //     $rawMaterial = RawMaterial::create($validated);

    //     if ($validated['current_stock'] > 0) {
    //         StockTransaction::create([
    //             'raw_material_id' => $rawMaterial->id,
    //             'type' => 'in',
    //             'quantity' => $validated['current_stock'],
    //             'reference_type' => 'initial_stock',
    //             'notes' => 'Setup stok awal',
    //             'created_by' => Auth::id()
    //         ]);
    //     }

    //     return redirect()->route('admin.raw-materials.index')->with('success', 'Bahan baku berhasil ditambahkan!');
    // }



    // public function store(Request $request)
    // {
    //     // 1. Validasi input sesuai form di gambar
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'sku' => 'nullable|string|max:50',
    //         'stock' => 'required|numeric|min:0',
    //         'min_stock' => 'required|numeric|min:0',
    //         'unit' => 'required|string|max:20',
    //     ]);

    //     // 2. Buat objek Bahan Baku baru
    //     $material = new \App\Models\RawMaterial(); // Sesuaikan nama modelnya
    //     $material->name = $request->name;
    //     $material->sku = $request->sku;
    //     $material->stock = $request->stock;
    //     $material->min_stock = $request->min_stock;
    //     $material->unit = $request->unit;

    //     // 3. Simpan
    //     $material->save();

    //     return redirect()->route('admin.raw-materials.index')->with('success', 'Bahan baku berhasil ditambahkan!');
    // }

    public function store(Request $request)
    {
        // 1. Validasi (Sesuaikan dengan nama input dari form)
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:50',
            'current_stock' => 'required|numeric',
            'reorder_level' => 'required|numeric', // Input form kamu namanya reorder_level
            'unit' => 'required|string',
        ]);

        // 2. Logika Restore
        $existing = \App\Models\RawMaterial::onlyTrashed()
            ->where('name', $request->name)
            ->first();

        if ($existing) {
            $existing->restore();
            $existing->update([
                'sku' => $request->sku,
                'current_stock' => $request->current_stock, // Sesuaikan DB
                'minimum_stock' => $request->reorder_level, // Sesuaikan DB
                'unit' => $request->unit,
            ]);

            return redirect()->route('admin.raw-materials.index')
                ->with('success', 'Bahan baku lama ditemukan dan telah diaktifkan kembali!');
        }

        // 3. Simpan Baru (BAGIAN INI YANG ERROR TADI)
        $material = new \App\Models\RawMaterial();
        $material->name = $request->name;
        $material->sku = $request->sku;
        $material->unit = $request->unit;

        // SEBELAH KIRI: Nama kolom di Database (Hasil tinker kamu)
        // SEBELAH KANAN: Nama input di Form (Hasil dd kamu)
        $material->current_stock = $request->current_stock;
        $material->minimum_stock = $request->reorder_level;

        $material->save();

        return redirect()->route('admin.raw-materials.index')
            ->with('success', 'Bahan baku berhasil ditambahkan!');
    }
    
    public function show(RawMaterial $rawMaterial)
    {
        $transactions = $rawMaterial->transactions()->with('creator')->latest()->get();
        return view('admin.raw-materials.show', compact('rawMaterial', 'transactions'));
    }

    public function edit(RawMaterial $rawMaterial)
    {
        return view('admin.raw-materials.edit', compact('rawMaterial'));
    }

    public function update(Request $request, RawMaterial $rawMaterial)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:50|unique:raw_materials,sku,' . $rawMaterial->id,
            'unit' => 'required|string|max:50',
            'current_stock' => 'required|numeric|min:0',
            'reorder_level' => 'required|numeric|min:0',
        ]);

        $difference = $validated['current_stock'] - $rawMaterial->current_stock;

        if ($difference != 0) {
            StockTransaction::create([
                'raw_material_id' => $rawMaterial->id,
                'type' => $difference > 0 ? 'in' : 'out',
                'quantity' => abs($difference),
                'reference_type' => 'manual_adjustment',
                'notes' => 'Koreksi stok manual',
                'created_by' => Auth::id()
            ]);
        }

        $rawMaterial->update($validated);

        return redirect()->route('admin.raw-materials.index')->with('success', 'Bahan baku diperbarui!');
    }

    // public function destroy(RawMaterial $rawMaterial)
    // {
    //     $rawMaterial->delete();
    //     return redirect()->route('admin.raw-materials.index')->with('success', 'Bahan baku dihapus!');
    // }

    // public function destroy(RawMaterial $rawMaterial)
    // {
    //     // 1. Cek apakah bahan baku ini masih dipakai di resep produk manapun
    //     // Ganti 'productRecipes' dengan nama fungsi relasi yang ada di Model RawMaterial kamu
    //     if ($rawMaterial->productRecipes()->exists()) {
    //         return redirect()->back()->with('error', 'Tidak bisa menghapus "' . $rawMaterial->name . '" karena masih digunakan dalam resep produk. Hapus dulu bahan ini dari resep terkait!');
    //     }

    //     try {
    //         // 2. Jika tidak ada relasi, baru hapus
    //         $rawMaterial->delete();
    //         return redirect()->route('admin.raw-materials.index')->with('success', 'Bahan baku berhasil dihapus!');
    //     } catch (\Exception $e) {
    //         // Ubah baris ini sementara untuk melihat "tersangka" utamanya
    //         dd($e->getMessage());
    //     }
    // }

    public function destroy(RawMaterial $rawMaterial)
    {
        // Laravel akan otomatis melakukan Soft Delete
        $rawMaterial->delete();

        return redirect()->route('admin.raw-materials.index')
            ->with('success', 'Bahan baku berhasil dihapus!');
    }
}
