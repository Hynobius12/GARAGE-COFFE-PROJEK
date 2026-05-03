<x-admin-layout>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-heading font-semibold text-primary">Laporan Penjualan</h2>
            <p class="text-sm text-gray-500 mt-1">Pantau performa penjualan dan export data ke Excel / PDF.</p>
        </div>
        
        <div class="flex space-x-2">
            <a href="{{ route('admin.reports.excel', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm font-medium transition flex items-center shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export Excel
            </a>
            <a href="{{ route('admin.reports.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-medium transition flex items-center shadow-sm" target="_blank">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                Export PDF
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 mb-6">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="flex items-end space-x-4">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="rounded-md border-gray-300 text-sm focus:ring-primary focus:border-primary">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="rounded-md border-gray-300 text-sm focus:ring-primary focus:border-primary">
            </div>
            <div>
                <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-700">Filter</button>
            </div>
        </form>
    </div>

    <!-- Chart -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 mb-6 relative">
        <h3 class="text-sm font-bold text-gray-700 mb-4 uppercase tracking-wider">Grafik Pendapatan</h3>
        <div class="h-64">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <h3 class="font-bold text-gray-700">Data Transaksi <span class="text-gray-400 font-normal text-sm">({{ $orders->count() }} transaksi)</span></h3>
            <span class="text-xs font-medium bg-primary text-accent px-2 py-1 rounded">Total: Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sumber</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-800 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono font-medium text-gray-900">{{ $order->order_number ?? $order->order_code }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $order->customer_name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($order['created_at'])->format('d M Y, H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 rounded text-xs font-bold uppercase {{ $order['type'] === 'POS' ? 'bg-blue-100 text-blue-700' : 'bg-indigo-100 text-indigo-700' }}">
                                {{ isset($order->order_code) ? 'Web' : 'POS' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span class="px-2 py-1 bg-gray-100 rounded text-xs uppercase">{{ $order->payment_method ?? 'QRIS (Web)' }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-primary text-right">Rp {{ number_format($order['total_amount'], 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">Tidak ada transaksi pada periode ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(200, 169, 126, 0.5)'); // Accent color
        gradient.addColorStop(1, 'rgba(200, 169, 126, 0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: @json($chartValues),
                    borderColor: '#c8a97e', // Accent
                    backgroundColor: gradient,
                    borderWidth: 2,
                    pointBackgroundColor: '#1a1a1a', // Primary
                    pointBorderColor: '#c8a97e',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let value = context.raw;
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false } },
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [2, 4], color: '#e5e7eb' },
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return (value / 1000000).toFixed(1) + 'M';
                                } else if (value >= 1000) {
                                    return (value / 1000).toFixed(0) + 'K';
                                }
                                return value;
                            }
                        }
                    }
                }
            }
        });
    </script>
</x-admin-layout>
