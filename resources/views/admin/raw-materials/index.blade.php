<x-admin-layout>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-heading font-semibold text-primary">Inventori Bahan Baku</h2>
            <p class="text-sm text-gray-600 mt-1">Kelola stok material dan bahan dasar.</p>
        </div>
        <a href="{{ route('admin.raw-materials.create') }}" class="bg-primary hover:bg-gray-800 text-white font-medium py-2 px-4 rounded transition">
            + Tambah Stok
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($rawMaterials as $item)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $item->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $item->sku ?: '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold">
                        {{ $item->current_stock }} {{ $item->unit }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        @if($item->current_stock <= 0)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Habis</span>
                        @elseif($item->current_stock <= $item->reorder_level)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Menipis</span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aman</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                        <a href="{{ route('admin.raw-materials.show', $item) }}" class="text-blue-600 hover:text-blue-900">Histori</a>
                        <a href="{{ route('admin.raw-materials.edit', $item) }}" class="text-indigo-600 hover:text-indigo-900">Edit / Adjust</a>
                        <form action="{{ route('admin.raw-materials.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Hapus bahan baku ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada bahan baku yang didaftarkan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>
