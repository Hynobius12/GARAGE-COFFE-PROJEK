<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Status Order {{ $order->order_code }} — Garage Coffee</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Inter', sans-serif; } .font-heading { font-family: 'Playfair Display', serif; }</style>
    @if(in_array($order->status, ['pending_payment', 'payment_uploaded', 'confirmed', 'processing']))
        <meta http-equiv="refresh" content="30">
    @endif
</head>
<body class="min-h-screen bg-gradient-to-br from-[#0f0f11] via-[#1a1816] to-[#0a0a0a] text-gray-200 flex flex-col items-center justify-center px-4 py-12">

    <div class="w-full max-w-md">
        <div class="text-center mb-6">
            <h1 class="font-heading text-3xl font-bold text-amber-400 mb-1">GARAGE COFFEE</h1>
            <p class="text-gray-500 text-xs tracking-widest uppercase">Status Pesanan</p>
        </div>

        <div class="bg-[#1c1c21] border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
            <!-- Header -->
            <div class="px-6 py-5 border-b border-white/10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500">Kode Pesanan</p>
                        <p class="text-xl font-bold text-white">{{ $order->order_code }}</p>
                    </div>
                    @php
                        $statusConfig = [
                            'pending_payment'  => ['label' => 'Menunggu Pembayaran', 'color' => 'bg-gray-700 text-gray-300', 'icon' => '⏳'],
                            'payment_uploaded' => ['label' => 'Bukti Dikirim', 'color' => 'bg-amber-500/20 text-amber-400', 'icon' => '📤'],
                            'confirmed'        => ['label' => 'Dikonfirmasi ✓', 'color' => 'bg-green-500/20 text-green-400', 'icon' => '✅'],
                            'cancelled'        => ['label' => 'Dibatalkan', 'color' => 'bg-red-500/20 text-red-400', 'icon' => '❌'],
                        ][$order->status] ?? ['label' => $order->status, 'color' => 'bg-gray-700 text-gray-400', 'icon' => '?'];
                    @endphp
                    <span class="px-3 py-1.5 text-xs font-bold rounded-xl {{ $statusConfig['color'] }}">
                        {{ $statusConfig['icon'] }} {{ $statusConfig['label'] }}
                    </span>
                </div>
            </div>

            <!-- Progress Stepper -->
            <div class="px-6 py-5 border-b border-white/10">
                @php
                    $steps = [
                        'Dibuat',
                        'Dibayar',
                        'Dikonfirmasi',
                        'Diproses',
                        'Selesai'
                    ];
                    $activeStep = match($order->status) {
                        'pending_payment'  => 0,
                        'payment_uploaded' => 1,
                        'confirmed'        => 2,
                        'processing'       => 3,
                        'completed'        => 4,
                        default            => 0,
                    };
                @endphp
                <div class="flex items-center">
                    @foreach($steps as $i => $step)
                        <div class="flex flex-col items-center flex-1">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center text-[10px] font-bold border-2 
                                {{ $i <= $activeStep ? 'bg-amber-500 border-amber-500 text-gray-900' : 'border-gray-600 text-gray-600 bg-transparent' }}">
                                {{ $i < $activeStep ? '✓' : ($i + 1) }}
                            </div>
                            <p class="text-[9px] text-center mt-1.5 {{ $i <= $activeStep ? 'text-amber-400' : 'text-gray-600' }} leading-tight">
                                {{ $step }}
                            </p>
                        </div>
                        @if(!$loop->last)
                            <div class="flex-1 h-0.5 mb-4 {{ $i < $activeStep ? 'bg-amber-500' : 'bg-gray-700' }}"></div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Order Items -->
            <div class="px-6 py-4 border-b border-white/10">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Item Pesanan</h3>
                <div class="space-y-2">
                    @foreach($order->items as $item)
                    <div class="flex justify-between items-start text-sm">
                        <div>
                            <p class="text-white font-medium">{{ $item['name'] }}</p>
                            @if(!empty($item['variant'])) <p class="text-xs text-gray-500">{{ $item['variant'] }}</p> @endif
                        </div>
                        <div class="text-right ml-4">
                            <p class="text-gray-400 text-xs">{{ $item['qty'] }}×</p>
                            <p class="text-amber-400 font-semibold">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-4 pt-3 border-t border-white/10 flex justify-between font-bold">
                    <span class="text-white">Total</span>
                    <span class="text-amber-400 text-lg">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Upload Proof Section (if pending) -->
            @if($order->status === 'pending_payment')
            <div class="px-6 py-5 bg-amber-500/5" x-data="{ uploading: false, done: false }">
                <h3 class="text-sm font-bold text-amber-400 mb-3">Upload Bukti Pembayaran QRIS</h3>
                <p class="text-xs text-gray-400 mb-4">Setelah scan QRIS dan bayar, upload screenshot bukti transfer Anda di sini.</p>
                
                <form id="uploadForm" enctype="multipart/form-data" @submit.prevent="submitProof()">
                    @csrf
                    <label class="block w-full border-2 border-dashed border-amber-500/30 hover:border-amber-500/60 rounded-xl p-4 text-center cursor-pointer transition group" for="proof-input">
                        <input type="file" id="proof-input" name="payment_proof" accept="image/*" class="hidden" required
                               @change="$event.target.files[0] ? $refs.fileName.textContent = $event.target.files[0].name : null">
                        <svg class="w-8 h-8 text-amber-500/50 mx-auto mb-2 group-hover:text-amber-500/80 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p x-ref="fileName" class="text-xs text-gray-400">Klik untuk pilih foto bukti bayar</p>
                        <p class="text-[10px] text-gray-600 mt-1">JPG, PNG, WEBP (maks. 5MB)</p>
                    </label>

                    <button type="submit" 
                            :disabled="uploading"
                            class="w-full mt-3 bg-amber-500 hover:bg-amber-400 disabled:opacity-50 text-gray-900 font-bold py-3 rounded-xl transition shadow-md text-sm">
                        <span x-show="!uploading">📤 Upload Bukti Bayar</span>
                        <span x-show="uploading">Mengupload...</span>
                    </button>
                </form>

                <div x-show="done" style="display:none" class="mt-3 text-center text-green-400 text-sm font-medium">
                    ✅ Bukti berhasil dikirim! Halaman akan refresh...
                </div>
            </div>
            @elseif($order->status === 'payment_uploaded')
            <div class="px-6 py-5 text-center">
                <div class="w-14 h-14 bg-amber-500/10 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-amber-400 font-bold">Bukti sudah dikirim!</p>
                <p class="text-gray-400 text-xs mt-1">Sedang menunggu konfirmasi dari tim kami. Halaman ini auto-refresh setiap 30 detik.</p>
            </div>
            @elseif($order->status === 'confirmed')
            <div class="px-6 py-5 text-center">
                <div class="w-14 h-14 bg-indigo-500/10 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <p class="text-indigo-400 font-bold text-lg">Pembayaran Dikonfirmasi!</p>
                <p class="text-gray-400 text-sm mt-1">Pesanan Anda masuk ke antrean. Mohon tunggu sebentar ☕</p>
            </div>
            @elseif($order->status === 'processing')
            <div class="px-6 py-5 text-center">
                <div class="w-14 h-14 bg-green-500/10 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7 text-green-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <p class="text-green-400 font-bold text-lg">Sedang Diproses!</p>
                <p class="text-gray-400 text-sm mt-1">Barista/Koki kami sedang menyiapkan pesanan Anda. ✨</p>
            </div>
            @elseif($order->status === 'completed')
            <div class="px-6 py-5 text-center">
                <div class="w-14 h-14 bg-emerald-500/10 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-emerald-400 font-bold text-lg">Pesanan Selesai!</p>
                <p class="text-gray-400 text-sm mt-1">Silakan ambil pesanan Anda atau nikmati hidangan Anda. Terima kasih! 🎉</p>
            </div>
            @elseif($order->status === 'cancelled')
            <div class="px-6 py-5 text-center">
                <p class="text-red-400 font-bold">Pesanan Dibatalkan</p>
                <p class="text-gray-400 text-xs mt-1">Silakan buat pesanan baru atau hubungi staff.</p>
                <a href="{{ route('menu.index') }}" class="inline-block mt-3 text-xs text-amber-400 hover:underline">← Kembali ke Menu</a>
            </div>
            @endif

            <!-- Footer -->
            <div class="px-6 py-4 text-center border-t border-white/10">
                <p class="text-xs text-gray-600">{{ $order->created_at->format('d M Y, H:i') }} · Garage Coffee</p>
                <a href="{{ route('menu.index') }}" class="text-xs text-amber-500/60 hover:text-amber-400 mt-1 block">← Kembali ke Menu</a>
            </div>
        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        function submitProof() {
            const form = document.getElementById('uploadForm');
            const formData = new FormData(form);
            this.uploading = true;

            fetch("{{ route('order.proof', $order) }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: formData,
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    this.done = true;
                    setTimeout(() => location.reload(), 1500);
                }
            })
            .catch(() => {
                this.uploading = false;
                alert('Gagal upload. Coba lagi.');
            });
        }
    </script>
</body>
</html>
