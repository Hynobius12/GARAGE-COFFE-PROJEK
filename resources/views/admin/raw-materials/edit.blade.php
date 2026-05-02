<x-admin-layout>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-heading font-semibold text-primary">Edit Bahan Baku</h2>
            <p class="text-sm text-gray-500 mt-1">Gunakan form ini untuk memperbaiki nama atau melakukan penyesuaian (adjustment) stok secara manual.</p>
        </div>
        <a href="{{ route('admin.raw-materials.index') }}" class="text-gray-500 hover:text-primary">Kembali</a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden p-6 max-w-2xl">
        <form action="{{ route('admin.raw-materials.update', $rawMaterial) }}" method="POST">
            @csrf @method('PUT')
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Bahan Baku</label>
                <input type="text" name="name" class="w-full rounded-md border border-gray-300 px-3 py-2" required value="{{ old('name', $rawMaterial->name) }}">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">SKU (Kode Barang)</label>
                <input type="text" name="sku" class="w-full rounded-md border border-gray-300 px-3 py-2" value="{{ old('sku', $rawMaterial->sku) }}">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Stok Saat Ini (Koreksi)</label>
                    <input type="number" name="current_stock" class="w-full rounded-md border-orange-500 px-3 py-2 focus:ring-orange-500" required value="{{ old('current_stock', $rawMaterial->current_stock) }}">
                    <p class="text-[10px] text-gray-500 mt-1">Koreksi otomatis akan tercatat historinya.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Batas Minimum (Re-order)</label>
                    <input type="number" name="reorder_level" class="w-full rounded-md border border-gray-300 px-3 py-2" required value="{{ old('reorder_level', $rawMaterial->reorder_level) }}">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Satuan</label>
                    <input type="text" name="unit" class="w-full rounded-md border border-gray-300 px-3 py-2" required value="{{ old('unit', $rawMaterial->unit) }}">
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-primary text-white px-6 py-2 rounded font-medium hover:bg-gray-800">Perbarui Bahan Baku</button>
            </div>
        </form>
    </div>
</x-admin-layout>
