<x-admin-layout>
    <div class="mb-6 flex justify-between items-center sm:flex-row flex-col sm:space-y-0 space-y-4">
        <div>
            <h2 class="text-2xl font-heading font-semibold text-primary">Detail Pesanan</h2>
            <p class="text-sm text-gray-500 mt-1">#{{ $order->order_number }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.orders.index') }}" class="text-gray-500 hover:text-primary py-2 px-4">Kembali</a>
            <button onclick="printReceipt()" class="bg-gray-800 hover:bg-gray-700 text-white font-medium py-2 px-6 rounded shadow-sm flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Web Struk
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informasi Umum -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Item Pesanan</h3>
            
            <div class="space-y-4">
                @foreach($order->items as $item)
                <div class="flex justify-between items-start pt-2">
                    <div>
                        <p class="font-bold text-gray-900">{{ $item->product->name }} <span class="bg-gray-200 px-2 py-0.5 rounded text-xs ml-2">x{{ $item->quantity }}</span></p>
                        @if($item->variant)
                            <p class="text-sm text-gray-500">Varian: {{ $item->variant->name }}</p>
                        @endif
                        @if($item->special_instructions)
                            <p class="text-xs italic text-red-500 mt-1">Catatan: {{ $item->special_instructions }}</p>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500">@ Rp {{ number_format($item->unit_price, 0, ',', '.') }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-8 pt-4 border-t border-gray-200">
                <div class="flex justify-between text-gray-600 mb-2">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-gray-600 mb-2">
                    <span>Pajak (10%)</span>
                    <span>Rp {{ number_format($order->tax, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-gray-900 font-bold text-lg mt-4 pt-4 border-t border-gray-200">
                    <span>Total Keseluruhan</span>
                    <span class="text-accent">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Detail Transaksi</h3>
                
                <div class="space-y-3">
                    <div>
                        <span class="block text-xs text-gray-500">Status</span>
                        @if($order->status === 'completed')
                            <span class="font-bold text-green-600">SELESAI DILAYANI</span>
                        @else
                            <span class="font-bold text-yellow-600 uppercase">{{ $order->status }}</span>
                        @endif
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500">Waktu Order</span>
                        <span class="font-medium text-gray-900">{{ $order->created_at->format('d/m/Y H:i:s') }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500">Kasir / Operator</span>
                        <span class="font-medium text-gray-900">{{ $order->cashier->name ?? 'User Dihapus' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500">Nama Pelanggan</span>
                        <span class="font-medium text-gray-900 uppercase">{{ $order->customer_name ?? 'UMUM (WALK-IN)' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500">Metode Pembayaran</span>
                        <span class="font-medium text-gray-900 uppercase">{{ $order->payment_method }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Area Struk Print Khusus -->
    <div id="receiptArea" style="display: none;" class="print-visible bg-white p-4 font-mono text-xs w-[300px] text-black">
        <div class="text-center mb-4">
            <h2 class="text-lg font-bold">GARAGE COFFEE</h2>
            <p>Jl. Contoh Industri No. 123</p>
            <p>--------------------------------</p>
        </div>
        
        <p>No    : {{ $order->order_number }}</p>
        <p>Tgl   : {{ $order->created_at->format('d/m/Y H:i') }}</p>
        <p>Kasir : {{ $order->cashier->name ?? '-' }}</p>
        <p>Plg   : {{ $order->customer_name ?? 'Umum' }}</p>
        <p>--------------------------------</p>
        
        <div class="space-y-1 mb-2">
            @foreach($order->items as $item)
            <div>{{ $item->product->name }} {{ $item->variant ? '('.$item->variant->name.')' : '' }}</div>
            <div class="flex justify-between">
                <span>{{ $item->quantity }} x {{ number_format($item->unit_price, 0, ',', '.') }}</span>
                <span>{{ number_format($item->subtotal, 0, ',', '.') }}</span>
            </div>
            @endforeach
        </div>

        <p>--------------------------------</p>
        <div class="flex justify-between"><span>Subtotal</span><span>{{ number_format($order->subtotal, 0, ',', '.') }}</span></div>
        <div class="flex justify-between"><span>Tax (10%)</span><span>{{ number_format($order->tax, 0, ',', '.') }}</span></div>
        <div class="flex justify-between text-sm mt-2 font-bold"><span>TOTAL</span><span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span></div>
        <div class="mt-2 text-center text-[10px]"><p>TIPE BAYAR: {{ strtoupper($order->payment_method) }}</p></div>

        <p class="mt-6 text-center">--- TERIMA KASIH ---</p>
    </div>

    <style>
        @media print {
            body * { visibility: hidden; }
            .print-visible, .print-visible * { visibility: visible; }
            .print-visible { position: absolute; left: 0; top: 0; width: 100%; max-width: 80mm; margin: 0; padding: 0; }
            @page { margin: 0; size: 80mm auto; }
        }
    </style>

    <script>
        function printReceipt() {
            document.getElementById('receiptArea').style.display = 'block';
            window.print();
            setTimeout(() => {
                document.getElementById('receiptArea').style.display = 'none';
            }, 500);
        }
    </script>
</x-admin-layout>
