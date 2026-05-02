<x-admin-layout>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-heading font-semibold text-primary">Varian: {{ $product->name }}</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola ukuran atau jenis tambahan (seperti Hot/Ice, Small/Large) beserta tambahan harganya.</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="text-gray-500 hover:text-primary border border-gray-300 px-4 py-2 rounded">Kembali ke Produk</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Form Tambah Varian -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-xl shadow border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Buat Varian Baru</h3>
                <form action="{{ route('admin.products.variants.store', $product) }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Varian <span class="text-red-500">*</span></label>
                        <input type="text" name="name" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:ring-accent" required placeholder="Cth: Large / Ice">
                    </div>
                    
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tambahan Harga (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="additional_price" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:ring-accent" required placeholder="0 jika gratis" value="0">
                        <p class="text-xs text-gray-400 mt-1">Isi 0 jika varian ini tidak menambah harga dasar.</p>
                    </div>

                    <div class="mb-5 flex items-center">
                        <input type="checkbox" name="is_available" id="is_available" checked class="rounded border-gray-300 text-accent focus:ring-accent h-4 w-4">
                        <label for="is_available" class="ml-2 block text-sm text-gray-700">
                            Tersedia saat ini
                        </label>
                    </div>

                    <button type="submit" class="w-full bg-accent text-white font-medium py-2.5 rounded shadow hover:bg-yellow-700 transition">Tambahkan Varian</button>
                </form>
            </div>
        </div>

        <!-- Daftar Varian -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-700">Daftar Varian Aktif</h3>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-white">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Varian</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Tambahan (Rp)</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($product->variants as $variant)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                {{ $variant->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-medium text-accent">
                                + Rp {{ number_format($variant->additional_price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($variant->is_available)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Available</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Habis</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <form action="{{ route('admin.products.variants.destroy', [$product, $variant]) }}" method="POST" onsubmit="return confirm('Hapus varian ini secara permanen?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 px-3 py-1 rounded">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                Tidak ada satupun varian.
                                <br><span class="text-xs mt-1">Tambahkan opsi seperti "Hot/Ice" atau "Ukuran Besar" di sini.</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
