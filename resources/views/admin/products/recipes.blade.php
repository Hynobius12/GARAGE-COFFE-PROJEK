<x-admin-layout>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-heading font-semibold text-primary">Resep: {{ $product->name }}</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola komposisi bahan baku untuk produk ini.</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="text-gray-500 hover:text-primary">Kembali</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Form Tambah Bahan -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah Ke Resep</h3>
                <form action="{{ route('admin.products.recipes.store', $product) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bahan Baku</label>
                        <select name="raw_material_id" class="w-full rounded-md border border-gray-300 px-3 py-2" required>
                            <option value="">-- Pilih Bahan --</option>
                            @foreach($rawMaterials as $material)
                                <option value="{{ $material->id }}">{{ $material->name }} ({{ $material->unit }})</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Dibutuhkan</label>
                        <input type="number" step="0.01" name="quantity_needed" class="w-full rounded-md border border-gray-300 px-3 py-2" required placeholder="Contoh: 15.5">
                    </div>

                    <button type="submit" class="w-full bg-primary text-white py-2 rounded shadow hover:bg-gray-800 transition">Tambahkan</button>
                </form>
            </div>
        </div>

        <!-- Daftar Resep -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bahan Baku</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah/Takaran</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($product->recipes as $recipe)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $recipe->rawMaterial->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold">
                                {{ $recipe->quantity_needed }} {{ $recipe->rawMaterial->unit }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <form action="{{ route('admin.products.recipes.destroy', [$product, $recipe]) }}" method="POST" onsubmit="return confirm('Hapus dari resep?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-gray-500">Belum ada bahan baku di resep ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
