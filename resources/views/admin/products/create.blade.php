<x-admin-layout>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-heading font-semibold text-primary">Tambah Produk</h2>
        </div>
        <a href="{{ route('admin.products.index') }}" class="text-gray-500 hover:text-primary">Kembali</a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden p-6 max-w-4xl">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Produk</label>
                    <input type="text" name="name" class="w-full rounded-md border border-gray-300 px-3 py-2" required value="{{ old('name') }}">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="category_id" class="w-full rounded-md border border-gray-300 px-3 py-2" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga Dasar (Rp)</label>
                    <input type="number" name="base_price" class="w-full rounded-md border border-gray-300 px-3 py-2" required value="{{ old('base_price', 0) }}">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Produk</label>
                    <input type="file" name="image_file" class="w-full">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full rounded-md border border-gray-300 px-3 py-2">{{ old('description') }}</textarea>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Infromasi Alergen (Opsional)</label>
                <textarea name="allergen_info" rows="2" class="w-full rounded-md border border-gray-300 px-3 py-2">{{ old('allergen_info') }}</textarea>
            </div>

            <div class="flex space-x-6 mb-6">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_available" class="rounded border-gray-300 text-accent focus:ring-accent" checked>
                    <span class="ml-2 text-gray-700">Tersedia (Bisa Dipesan)</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_featured" class="rounded border-gray-300 text-accent focus:ring-accent">
                    <span class="ml-2 text-gray-700">Produk Rekomendasi (Featured)</span>
                </label>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-primary text-white px-6 py-2 rounded font-medium hover:bg-gray-800">Simpan Produk</button>
            </div>
        </form>
    </div>
</x-admin-layout>
