<x-admin-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-heading font-semibold text-primary">Dashboard</h2>
        <p class="text-sm text-gray-500 mt-1">Selamat datang, {{ Auth::user()->name }}. Pantau semua aktivitas Garage Coffee.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Penjualan Hari Ini -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-start justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Penjualan Hari Ini</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
                <p class="text-xs text-gray-400 mt-1">Completed orders</p>
            </div>
            <div class="bg-green-100 p-2.5 rounded-lg">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <!-- Total Pesanan -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-start justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pesanan Hari Ini</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $todayOrders }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $todayItems }} item terjual</p>
            </div>
            <div class="bg-blue-100 p-2.5 rounded-lg">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
        </div>

        <!-- QRIS Pending -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-start justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">QRIS Perlu Konfirmasi</p>
                <p class="text-2xl font-bold {{ $qrisPending > 0 ? 'text-amber-600' : 'text-gray-900' }} mt-1">{{ $qrisPending }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $qrisUnpaid }} menunggu bayar</p>
            </div>
            <div class="bg-amber-100 p-2.5 rounded-lg">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
            </div>
        </div>

        <!-- Stok Kritis -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-start justify-between">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Stok Kritis</p>
                <p class="text-2xl font-bold {{ $lowStock > 0 ? 'text-red-600' : 'text-gray-900' }} mt-1">{{ $lowStock }}</p>
                <p class="text-xs text-gray-400 mt-1">Item bahan baku</p>
            </div>
            <div class="bg-red-100 p-2.5 rounded-lg">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
        </div>
    </div>

    <!-- Chart + QRIS Panel -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Sales Chart -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-semibold text-gray-800">Penjualan 7 Hari Terakhir</h3>
                <span class="text-xs text-gray-400">Completed orders only</span>
            </div>
            <canvas id="salesChart" height="110"></canvas>
        </div>

        <!-- QRIS Pending Widget -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-semibold text-gray-800">QRIS Menunggu Konfirmasi</h3>
                @if($pendingQrisOrders->count() > 0)
                    <span class="bg-amber-100 text-amber-700 text-xs font-bold px-2 py-0.5 rounded-full">{{ $pendingQrisOrders->count() }}</span>
                @endif
            </div>

            @forelse($pendingQrisOrders as $qo)
                <div class="mb-3 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-bold text-gray-900">{{ $qo->order_code }}</p>
                            <p class="text-xs text-gray-500">{{ $qo->customer_name }}</p>
                            <p class="text-sm font-semibold text-amber-600 mt-0.5">Rp {{ number_format($qo->total_amount, 0, ',', '.') }}</p>
                        </div>
                        <a href="{{ route('admin.qris-orders.show', $qo) }}" 
                           class="text-xs bg-amber-500 hover:bg-amber-600 text-white px-2 py-1 rounded-md transition">
                            Cek
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-400">
                    <svg class="w-10 h-10 mx-auto mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm">Tidak ada QRIS pending</p>
                </div>
            @endforelse

            @if($qrisPending > 5)
                <a href="{{ route('admin.qris-orders.index', ['status' => 'payment_uploaded']) }}" 
                   class="block text-center text-xs text-amber-600 hover:underline mt-2">
                    Lihat semua {{ $qrisPending }} pending →
                </a>
            @endif
        </div>
    </div>

    <!-- Recent Orders Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-base font-semibold text-gray-800">Pesanan Terbaru (Kasir)</h3>
            <a href="{{ route('admin.orders.index') }}" class="text-xs text-indigo-600 hover:underline">Lihat Semua →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kasir</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($recentOrders as $order)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3 text-sm font-bold text-gray-900">
                            <a href="{{ route('admin.orders.show', $order) }}" class="hover:text-indigo-600">{{ $order->order_number }}</a>
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ $order->customer_name ?? 'Umum' }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ $order->cashier->name ?? '-' }}</td>
                        <td class="px-6 py-3 text-sm font-semibold text-right text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-center">
                            @if($order->status === 'completed')
                                <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-green-100 text-green-700">SELESAI</span>
                            @elseif($order->status === 'cancelled')
                                <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-red-100 text-red-700">BATAL</span>
                            @elseif($order->status === 'processing')
                                <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-blue-100 text-blue-700">PROSES</span>
                            @else
                                <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-yellow-100 text-yellow-700">PENDING</span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-xs text-gray-400">{{ $order->created_at->format('d M, H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-400 text-sm">Belum ada pesanan hari ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($chartLabels),
                datasets: [
                    {
                        label: 'Revenue (Rp)',
                        data: @json($chartRevenue),
                        backgroundColor: 'rgba(200, 169, 126, 0.7)',
                        borderColor: 'rgba(200, 169, 126, 1)',
                        borderWidth: 2,
                        borderRadius: 6,
                        yAxisID: 'y',
                    },
                    {
                        label: 'Jumlah Order',
                        data: @json($chartOrders),
                        type: 'line',
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        borderWidth: 2,
                        pointRadius: 4,
                        tension: 0.4,
                        yAxisID: 'y1',
                        fill: true,
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { position: 'top', labels: { font: { size: 11 } } },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => {
                                if (ctx.datasetIndex === 0) {
                                    return 'Rp ' + parseInt(ctx.raw).toLocaleString('id-ID');
                                }
                                return ctx.dataset.label + ': ' + ctx.raw;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        position: 'left',
                        ticks: {
                            callback: (v) => 'Rp ' + parseInt(v/1000) + 'k',
                            font: { size: 10 }
                        },
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    y1: {
                        type: 'linear',
                        position: 'right',
                        ticks: { font: { size: 10 }, stepSize: 1 },
                        grid: { drawOnChartArea: false }
                    },
                    x: {
                        ticks: { font: { size: 10 } },
                        grid: { display: false }
                    }
                }
            }
        });
    </script>
</x-admin-layout>
