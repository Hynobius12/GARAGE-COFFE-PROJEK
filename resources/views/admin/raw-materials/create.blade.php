<x-admin-layout>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-heading font-semibold text-primary">Tambah Bahan Baku</h2>
        </div>
        <a href="{{ route('admin.raw-materials.index') }}" class="text-gray-500 hover:text-primary">Kembali</a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden p-6 max-w-2xl">
        <form action="{{ route('admin.raw-materials.store') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Bahan Baku</label>
                <input type="text" name="name" class="w-full rounded-md border border-gray-300 px-3 py-2" required value="{{ old('name') }}" placeholder="Contoh: Biji Kopi Arabica">
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">SKU (Kode Barang) - Opsional</label>
                <input type="text" name="sku" class="w-full rounded-md border border-gray-300 px-3 py-2" value="{{ old('sku') }}" placeholder="Contoh: BR-ARB-01">
                @error('sku') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Stok Awal</label>
                    <input type="number" name="current_stock" class="w-full rounded-md border border-gray-300 px-3 py-2" required value="{{ old('current_stock', 0) }}">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Batas Minimum (Re-order)</label>
                    <input type="number" name="reorder_level" class="w-full rounded-md border border-gray-300 px-3 py-2" required value="{{ old('reorder_level', 0) }}">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Satuan</label>
                    <input type="text" name="unit" class="w-full rounded-md border border-gray-300 px-3 py-2" required value="{{ old('unit', 'Gram') }}" placeholder="Gram, ml, Pcs">
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-primary text-white px-6 py-2 rounded font-medium hover:bg-gray-800">Simpan Bahan Baku</button>
            </div>
        </form>
    </div>
</x-admin-layout>
