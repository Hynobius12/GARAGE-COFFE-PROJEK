<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Garage Coffee — Halaman QRIS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-heading { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-[#0f0f11] via-[#1a1816] to-[#0a0a0a] text-gray-200 flex flex-col items-center justify-center px-4 py-12">

    <!-- Back link -->
    <div class="w-full max-w-lg mb-6">
        <a href="{{ route('menu.index') }}" class="text-gray-500 hover:text-amber-400 transition text-sm flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Menu
        </a>
    </div>

    <div class="w-full max-w-lg">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="font-heading text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-amber-200 mb-2">
                GARAGE COFFEE
            </h1>
            <p class="text-gray-400 text-sm tracking-widest uppercase">Pembayaran QRIS</p>
        </div>

        <!-- QRIS Card -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <!-- Top Bar -->
            <div class="bg-gradient-to-r from-amber-500 to-amber-400 px-6 py-4 text-center">
                <p class="text-amber-900 font-bold text-lg tracking-wide">Scan untuk Membayar</p>
                <p class="text-amber-800 text-xs mt-0.5">QRIS — Semua Dompet Digital & M-Banking</p>
            </div>

            <!-- QRIS Image -->
            <div class="p-6 flex flex-col items-center bg-white">
                <div class="bg-gray-50 border-4 border-amber-200 rounded-2xl p-3 shadow-inner">
                    <img src="{{ asset('images/qris.jpg') }}" alt="QRIS Garage Coffee" 
                         class="w-72 h-72 object-contain rounded-xl">
                </div>
                <p class="text-gray-500 text-xs mt-4 text-center">
                    Scan kode QR di atas menggunakan aplikasi favorit Anda.<br>
                    <span class="font-medium text-gray-700">GoPay, OVO, DANA, LinkAja, BCA, Mandiri, BRI, BNI, dll.</span>
                </p>
            </div>

            <!-- Steps -->
            <div class="bg-gray-50 px-6 py-5 border-t border-gray-100">
                <h3 class="text-sm font-bold text-gray-700 mb-4 text-center">Cara Pembayaran</h3>
                <div class="space-y-3">
                    <div class="flex items-start space-x-3">
                        <div class="w-6 h-6 bg-amber-500 text-white rounded-full flex items-center justify-center text-xs font-bold shrink-0">1</div>
                        <p class="text-sm text-gray-600">Pilih menu dari <a href="{{ route('menu.index') }}" class="text-amber-600 font-medium underline">E-Menu</a> dan tambahkan ke keranjang.</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-6 h-6 bg-amber-500 text-white rounded-full flex items-center justify-center text-xs font-bold shrink-0">2</div>
                        <p class="text-sm text-gray-600">Checkout dan dapatkan kode order unik Anda.</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-6 h-6 bg-amber-500 text-white rounded-full flex items-center justify-center text-xs font-bold shrink-0">3</div>
                        <p class="text-sm text-gray-600">Scan QRIS di atas sesuai total yang tertera, lalu upload bukti pembayaran.</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-6 h-6 bg-amber-500 text-white rounded-full flex items-center justify-center text-xs font-bold shrink-0">4</div>
                        <p class="text-sm text-gray-600">Tim kami konfirmasi dan pesanan Anda segera diproses! ☕</p>
                    </div>
                </div>
            </div>

            <!-- CTA -->
            <div class="px-6 py-5 text-center">
                <a href="{{ route('menu.index') }}" 
                   class="inline-block w-full bg-amber-500 hover:bg-amber-400 text-gray-900 font-bold py-3 rounded-xl transition shadow-md">
                    Pesan Sekarang via E-Menu
                </a>
                <p class="text-xs text-gray-400 mt-3">Ada kendala? Hubungi staff kami langsung di kasir.</p>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-gray-600 text-xs mt-8">© {{ date('Y') }} Garage Coffee. All rights reserved.</p>
    </div>

</body>
</html>
