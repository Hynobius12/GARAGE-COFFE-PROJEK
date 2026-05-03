<x-admin-layout>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-heading font-semibold text-primary">Produk Menu</h2>
            <p class="text-sm text-gray-600 mt-1">Kelola menu makanan dan minuman.</p>
        </div>
        <a href="{{ route('admin.products.create') }}"
            class="bg-primary hover:bg-gray-800 text-white font-medium py-2 px-4 rounded transition">
            + Tambah Produk
        </a>
    </div>

    <!-- @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif -->


    <div class="p-6"> <!-- Biasanya di dalam div pembungkus konten -->

        <!-- Notifikasi Sukses -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Notifikasi Error (Penting buat pas gagal hapus tadi) -->
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif
<!-- 
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Produk Menu</h2>
            <a href="{{ route('admin.products.create') }}" class="bg-primary text-white px-4 py-2 rounded">
                + Tambah Produk
            </a>
        </div> -->

        <!-- Tabel kamu ada di bawah sini -->
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga
                        Dasar</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($products as $product)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($product->image)
                                    <img class="h-10 w-10 rounded object-cover mr-3" src="{{ Storage::url($product->image) }}"
                                        alt="">
                                @else
                                    <div
                                        class="h-10 w-10 rounded bg-gray-200 flex items-center justify-center mr-3 text-gray-500">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $product->name }}
                                        @if($product->is_featured) <span
                                            class="ml-1 text-xs bg-yellow-100 text-yellow-800 px-1 rounded">Bintang</span>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($product->description, 30) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $product->category->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                            Rp {{ number_format($product->base_price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($product->is_available)
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Tersedia</span>
                            @else
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Habis</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <a href="{{ route('admin.products.variants.index', $product) }}"
                                class="text-orange-600 hover:text-orange-900">Varian</a>
                            <a href="{{ route('admin.products.recipes.index', $product) }}"
                                class="text-green-600 hover:text-green-900">Resep</a>
                            <a href="{{ route('admin.products.edit', $product) }}"
                                class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline"
                                onsubmit="return confirm('Hapus produk ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada produk yang ditambahkan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>