<x-admin-layout>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-heading font-semibold text-primary">Detail QRIS Order</h2>
            <p class="text-sm text-gray-500 mt-1">#{{ $qrisOrder->order_code }}</p>
        </div>
        <a href="{{ route('admin.qris-orders.index') }}" class="text-gray-500 hover:text-primary text-sm">← Kembali</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Items -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-base font-bold text-gray-800 mb-4 border-b pb-3">Item Pesanan</h3>
            <div class="space-y-3">
                @foreach($qrisOrder->items as $item)
                <div class="flex items-start justify-between py-2 border-b border-gray-50 last:border-0">
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">{{ $item['name'] }}</p>
                        @if(!empty($item['variant']))
                            <p class="text-xs text-gray-500">Varian: {{ $item['variant'] }}</p>
                        @endif
                        @if(!empty($item['notes']))
                            <p class="text-xs text-red-500 italic">Catatan: {{ $item['notes'] }}</p>
                        @endif
                    </div>
                    <div class="text-right shrink-0 ml-4">
                        <p class="text-sm text-gray-500">{{ $item['qty'] }} × Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                        <p class="font-semibold text-gray-900">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Total -->
            <div class="mt-6 pt-4 border-t border-gray-200">
                <div class="flex justify-between font-bold text-lg text-gray-900">
                    <span>Total Pembayaran</span>
                    <span class="text-amber-600">Rp {{ number_format($qrisOrder->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Notes -->
            @if($qrisOrder->notes)
            <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                <p class="text-xs text-gray-500 font-medium mb-1">Catatan Pelanggan:</p>
                <p class="text-sm text-gray-700">{{ $qrisOrder->notes }}</p>
            </div>
            @endif

            <!-- Action Buttons -->
            @if($qrisOrder->status === 'payment_uploaded')
            <div class="mt-6 flex space-x-3">
                <form action="{{ route('admin.qris-orders.confirm', $qrisOrder) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 rounded-xl transition shadow-sm">
                        ✓ Konfirmasi Pembayaran
                    </button>
                </form>
                <form action="{{ route('admin.qris-orders.reject', $qrisOrder) }}" method="POST" class="flex-1"
                      onsubmit="return confirm('Yakin ingin menolak pesanan ini?')">
                    @csrf
                    <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-2.5 rounded-xl transition shadow-sm">
                        ✗ Tolak / Batalkan
                    </button>
                </form>
            </div>
            @endif
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-5">
            <!-- Customer Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h3 class="text-sm font-bold text-gray-700 mb-3">Info Pelanggan</h3>
                <div class="space-y-2 text-sm">
                    <div>
                        <span class="text-xs text-gray-400">Nama</span>
                        <p class="font-semibold text-gray-900">{{ $qrisOrder->customer_name }}</p>
                    </div>
                    @if($qrisOrder->customer_phone)
                    <div>
                        <span class="text-xs text-gray-400">Telepon</span>
                        <p class="font-medium text-gray-800">{{ $qrisOrder->customer_phone }}</p>
                    </div>
                    @endif
                    <div>
                        <span class="text-xs text-gray-400">Meja / Tipe</span>
                        <p class="font-medium text-gray-800">{{ $qrisOrder->customer_table ?? 'Take Away' }}</p>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400">Waktu Order</span>
                        <p class="font-medium text-gray-800">{{ $qrisOrder->created_at->format('d M Y, H:i:s') }}</p>
                    </div>
                    @if($qrisOrder->confirmed_at)
                    <div>
                        <span class="text-xs text-gray-400">Dikonfirmasi</span>
                        <p class="font-medium text-green-700">{{ $qrisOrder->confirmed_at->format('d M Y, H:i:s') }}</p>
                    </div>
                    @endif
                </div>

                <!-- Status Badge -->
                <div class="mt-4 pt-3 border-t border-gray-100">
                    @php
                        $statusCss = [
                            'pending_payment'  => 'bg-gray-100 text-gray-600',
                            'payment_uploaded' => 'bg-amber-100 text-amber-700',
                            'confirmed'        => 'bg-green-100 text-green-700',
                            'cancelled'        => 'bg-red-100 text-red-700',
                        ][$qrisOrder->status] ?? 'bg-gray-100 text-gray-600';
                    @endphp
                    <span class="px-3 py-1 text-xs font-bold rounded-full {{ $statusCss }}">
                        {{ $qrisOrder->status_label }}
                    </span>
                </div>
            </div>

            <!-- Payment Proof -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h3 class="text-sm font-bold text-gray-700 mb-3">Bukti Pembayaran QRIS</h3>
                @if($qrisOrder->payment_proof)
                    <a href="{{ Storage::url($qrisOrder->payment_proof) }}" target="_blank">
                        <img src="{{ Storage::url($qrisOrder->payment_proof) }}" 
                             alt="Bukti Bayar" 
                             class="w-full rounded-lg border border-gray-200 hover:opacity-90 transition cursor-zoom-in">
                    </a>
                    <p class="text-xs text-gray-400 text-center mt-2">Klik untuk buka full size</p>
                @else
                    <div class="text-center py-6 text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="text-xs">Bukti bayar belum dikirim</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
