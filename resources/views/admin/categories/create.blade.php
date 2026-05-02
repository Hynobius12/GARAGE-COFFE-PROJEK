<x-admin-layout>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-heading font-semibold text-primary">Tambah Kategori</h2>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="text-gray-500 hover:text-primary">Kembali</a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden p-6 max-w-2xl">
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori</label>
                <input type="text" name="name" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-1 focus:ring-accent" required value="{{ old('name') }}">
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full rounded-md border border-gray-300 px-3 py-2">{{ old('description') }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Kategori</label>
                <input type="file" name="image_file" class="w-full">
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Urutan (Sort Order)</label>
                    <input type="number" name="sort_order" class="w-full rounded-md border border-gray-300 px-3 py-2" value="0">
                </div>
                <div class="flex items-end pb-2">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="is_active" class="rounded border-gray-300 text-accent focus:ring-accent" checked>
                        <span class="ml-2 text-gray-700">Aktif</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-primary text-white px-6 py-2 rounded font-medium hover:bg-gray-800">Simpan Kategori</button>
            </div>
        </form>
    </div>
</x-admin-layout>
