<x-admin-layout>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-heading font-semibold text-primary">QRIS Monitor</h2>
            <p class="text-sm text-gray-500 mt-1">Pantau dan kelola status pesanan dari E-Menu publik.</p>
        </div>
        <div class="flex items-center space-x-3">
            @php
                $pendingCount = \App\Models\QrisOrder::where('status','payment_uploaded')->count();
            @endphp
            @if($pendingCount > 0)
                <span class="bg-amber-100 text-amber-700 font-bold text-sm px-3 py-1 rounded-full">
                    {{ $pendingCount }} menunggu konfirmasi
                </span>
            @endif
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white p-4 rounded-xl shadow-sm mb-6 border border-gray-100">
        <form method="GET" action="{{ route('admin.qris-orders.index') }}" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1">
                <label class="block text-xs font-medium text-gray-500 mb-1">Cari (Kode / Nama)</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="w-full rounded-lg border-gray-300 text-sm focus:ring-amber-500 focus:border-amber-500" 
                       placeholder="GC-XXXXX atau nama...">
            </div>
            <div class="w-full md:w-48">
                <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                <select name="status" class="w-full rounded-lg border-gray-300 text-sm focus:ring-amber-500 focus:border-amber-500">
                    <option value="">Semua Status</option>
                    <option value="pending_payment"  {{ request('status') === 'pending_payment'  ? 'selected' : '' }}>Menunggu Bayar</option>
                    <option value="payment_uploaded" {{ request('status') === 'payment_uploaded' ? 'selected' : '' }}>Bukti Dikirim</option>
                    <option value="confirmed"        {{ request('status') === 'confirmed'        ? 'selected' : '' }}>Dikonfirmasi</option>
                    <option value="processing"       {{ request('status') === 'processing'       ? 'selected' : '' }}>Sedang Diproses</option>
                    <option value="completed"        {{ request('status') === 'completed'        ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled"        {{ request('status') === 'cancelled'        ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            <button type="submit" class="bg-primary hover:bg-gray-800 text-white px-5 py-2 rounded-lg text-sm font-medium shadow-sm">Filter</button>
            <a href="{{ route('admin.qris-orders.index') }}" class="text-gray-500 hover:text-primary text-sm py-2">Reset</a>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Order</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Meja</th>
                        <th class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-5 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Bukti</th>
                        <th class="px-5 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Dikonfirmasi Oleh</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                        <th class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 transition {{ $order->status === 'payment_uploaded' ? 'bg-amber-50/50' : '' }}">
                        <td class="px-5 py-4 text-sm font-bold text-gray-900">{{ $order->order_code }}</td>
                        <td class="px-5 py-4">
                            <p class="text-sm font-medium text-gray-900">{{ $order->customer_name }}</p>
                            @if($order->customer_phone)
                                <p class="text-xs text-gray-400">{{ $order->customer_phone }}</p>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-sm text-gray-500">{{ $order->customer_table ?? 'Take Away' }}</td>
                        <td class="px-5 py-4 text-sm font-semibold text-right text-gray-900">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-4 text-center">
                            @if($order->payment_proof)
                                <a href="{{ Storage::url($order->payment_proof) }}" target="_blank" 
                                   class="inline-flex items-center text-xs text-blue-600 hover:text-blue-800 bg-blue-50 px-2 py-1 rounded">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    Lihat
                                </a>
                            @else
                                <span class="text-xs text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-center">
                            @php
                                $colors = [
                                    'pending_payment'  => 'bg-gray-100 text-gray-600',
                                    'payment_uploaded' => 'bg-amber-100 text-amber-700',
                                    'confirmed'        => 'bg-indigo-100 text-indigo-700',
                                    'processing'       => 'bg-green-100 text-green-700',
                                    'completed'        => 'bg-emerald-100 text-emerald-800',
                                    'cancelled'        => 'bg-red-100 text-red-700',
                                ];
                            @endphp
                            <span class="px-2 py-0.5 text-xs font-bold rounded-full {{ $colors[$order->status] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-center text-xs">
                            @if($order->confirmedBy)
                                <span class="font-medium text-gray-700">{{ $order->confirmedBy->name }}</span>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-xs text-gray-400">{{ $order->created_at->format('d M, H:i') }}</td>
                        <td class="px-5 py-4 text-right text-sm space-x-2 whitespace-nowrap">
                            <a href="{{ route('admin.qris-orders.show', $order) }}" 
                               class="text-indigo-600 hover:text-indigo-900 font-medium">Detail</a>
                            
                            @if($order->status === 'pending_payment')
                                <form action="{{ route('admin.qris-orders.confirm', $order) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Konfirmasi pesanan ini secara manual tanpa bukti QRIS?')">
                                    @csrf
                                    <button type="submit" class="text-indigo-600 hover:text-indigo-800 font-bold bg-indigo-50 px-2 py-1 rounded">✓ Bayar Kasir</button>
                                </form>
                                <form action="{{ route('admin.qris-orders.reject', $order) }}" method="POST" class="inline" 
                                      onsubmit="return confirm('Batalkan pesanan ini?')">
                                    @csrf
                                    <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 px-2 py-1 rounded">✗ Batal</button>
                                </form>
                            @elseif($order->status === 'payment_uploaded')
                                <form action="{{ route('admin.qris-orders.confirm', $order) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-indigo-600 hover:text-indigo-800 font-bold bg-indigo-50 px-2 py-1 rounded">✓ Konfirmasi</button>
                                </form>
                                <form action="{{ route('admin.qris-orders.reject', $order) }}" method="POST" class="inline" 
                                      onsubmit="return confirm('Tolak pesanan ini?')">
                                    @csrf
                                    <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 px-2 py-1 rounded">✗ Tolak</button>
                                </form>
                            @elseif($order->status === 'confirmed')
                                <form action="{{ route('admin.qris-orders.process', $order) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-white hover:bg-green-600 font-bold bg-green-500 px-3 py-1 rounded shadow-sm">Proses Pesanan</button>
                                </form>
                            @elseif($order->status === 'processing')
                                <form action="{{ route('admin.qris-orders.complete', $order) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-white hover:bg-emerald-700 font-bold bg-emerald-600 px-3 py-1 rounded shadow-sm">Selesaikan</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                            Tidak ada data QRIS order.
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
