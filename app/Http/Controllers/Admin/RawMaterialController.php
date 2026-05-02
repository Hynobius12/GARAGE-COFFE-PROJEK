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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:50|unique:raw_materials,sku',
            'unit' => 'required|string|max:50',
            'current_stock' => 'required|numeric|min:0',
            'reorder_level' => 'required|numeric|min:0',
        ]);

        $rawMaterial = RawMaterial::create($validated);

        if ($validated['current_stock'] > 0) {
            StockTransaction::create([
                'raw_material_id' => $rawMaterial->id,
                'type' => 'in',
                'quantity' => $validated['current_stock'],
                'reference_type' => 'initial_stock',
                'notes' => 'Setup stok awal',
                'created_by' => Auth::id()
            ]);
        }

        return redirect()->route('admin.raw-materials.index')->with('success', 'Bahan baku berhasil ditambahkan!');
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

    public function destroy(RawMaterial $rawMaterial)
    {
        $rawMaterial->delete();
        return redirect()->route('admin.raw-materials.index')->with('success', 'Bahan baku dihapus!');
    }
}
