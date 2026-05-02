<x-admin-layout>
    <div class="mb-6 flex justify-between items-center sm:flex-row flex-col sm:space-y-0 space-y-4">
        <div>
            <h2 class="text-2xl font-heading font-semibold text-primary">Riwayat Pesanan</h2>
            <p class="text-sm text-gray-500 mt-1">Pantau seluruh detail pesanan pelanggan dari Kasir.</p>
        </div>
    </div>

    <!-- Filter/Search -->
    <div class="bg-white p-4 rounded-lg shadow-sm mb-6 border border-gray-100">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1 w-full relative">
                <label class="block text-xs font-medium text-gray-500 mb-1">Cari (Mencakup Nomor/Nama)</label>
                <div class="absolute inset-y-0 left-0 pl-3 pt-6 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" class="pl-10 w-full rounded-md border-gray-300 text-sm focus:ring-primary focus:border-primary" placeholder="Ketik disini...">
            </div>
            <div class="w-full md:w-1/4">
                <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                <select name="status" class="w-full rounded-md border-gray-300 text-sm focus:ring-primary focus:border-primary">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing (Dibuat)</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed (Selesai/Lunas)</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled (Batal)</option>
                </select>
            </div>
            <div>
                <button type="submit" class="bg-primary hover:bg-gray-800 text-white px-6 py-2 rounded-md font-medium text-sm w-full md:w-auto shadow-sm">Filter</button>
            </div>
        </form>
    </div>

    <!-- Tabel Orders -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kasir (Oleh)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ $order->order_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-medium">{{ $order->cashier->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 uppercase">{{ $order->customer_name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-accent font-semibold text-right">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($order->status === 'completed')
                                <span class="px-2 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800">SELESAI</span>
                            @elseif($order->status === 'cancelled')
                                <span class="px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800">BATAL</span>
                            @elseif($order->status === 'processing')
                                <span class="px-2 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-800">PROSES KDS</span>
                            @else
                                <span class="px-2 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-800">PENDING</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900 font-medium bg-indigo-50 px-3 py-1 rounded">Detail / Struk</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            Belum ada transaksi ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($orders->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</x-admin-layout>
