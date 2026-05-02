<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Garage Coffee') }} - Admin</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            .sidebar-link { display: flex; align-items: center; gap: 0.75rem; color: #d1d5db; padding: 0.625rem; border-radius: 0.5rem; transition: all 0.2s; font-size: 0.875rem; }
            .sidebar-link:hover { color: #fff; background: rgba(255,255,255,0.1); }
            .sidebar-link.active { background: rgba(200,169,126,0.2); color: #c8a97e; font-weight: 600; }
            .sidebar-section { font-size: 10px; text-transform: uppercase; letter-spacing: 0.1em; color: #6b7280; padding: 0 0.625rem; margin-top: 1.25rem; margin-bottom: 0.5rem; display: block; }
        </style>
    </head>
    <body class="font-sans antialiased text-primary bg-background">
        <div class="min-h-screen flex" x-data="{ sidebarOpen: window.innerWidth >= 1024 }">
            
            <!-- Sidebar Overlay (mobile) -->
            <div x-show="sidebarOpen && window.innerWidth < 1024" 
                 @click="sidebarOpen = false"
                 class="fixed inset-0 bg-black/50 z-10 lg:hidden" style="display:none;"></div>

            <!-- Sidebar -->
            <aside 
                class="fixed lg:sticky top-0 h-screen bg-[#1a1a1f] text-white w-64 flex-shrink-0 flex flex-col z-20 transition-transform duration-300 shadow-2xl"
                :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:-translate-x-full'"
            >
                <!-- Logo -->
                <div class="h-16 flex items-center justify-between px-5 border-b border-white/10 shrink-0">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-amber-500 rounded-lg flex items-center justify-center text-black font-bold text-xs">GC</div>
                        <h1 class="text-base font-heading text-amber-400 tracking-wider">GARAGE COFFEE</h1>
                    </div>
                    <button @click="sidebarOpen = false" class="lg:hidden text-gray-500 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                
                <!-- Nav -->
                <nav class="flex-1 overflow-y-auto p-3 space-y-0.5">
                    @role('owner')
                    <p class="sidebar-section">Utama</p>
                    
                    <a href="{{ route('admin.dashboard') }}" 
                       class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span>Dashboard</span>
                    </a>

                    <p class="sidebar-section">Menu & Produk</p>

                    <a href="{{ route('admin.categories.index') }}" 
                       class="sidebar-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                        <span>Kategori Menu</span>
                    </a>

                    <a href="{{ route('admin.products.index') }}" 
                       class="sidebar-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                        <span>Produk Menu</span>
                    </a>

                    <p class="sidebar-section">Transaksi</p>

                    <a href="{{ route('admin.orders.index') }}" 
                       class="sidebar-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        <span>Riwayat Pesanan</span>
                    </a>
                    @endrole

                    <p class="sidebar-section">QRIS E-Menu</p>

                    <a href="{{ route('admin.qris-orders.index') }}" 
                       class="sidebar-link {{ request()->routeIs('admin.qris-orders*') ? 'active' : '' }} relative">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        <span>QRIS Monitor</span>
                        @php $qrisBadge = \App\Models\QrisOrder::where('status','payment_uploaded')->count(); @endphp
                        @if($qrisBadge > 0)
                            <span class="ml-auto bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $qrisBadge }}</span>
                        @endif
                    </a>

                    @role('owner')
                    <p class="sidebar-section">Operasional</p>

                    <a href="{{ route('admin.raw-materials.index') }}" 
                       class="sidebar-link {{ request()->routeIs('admin.raw-materials*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <span>Inventori</span>
                    </a>

                    <a href="{{ route('admin.reports.index') }}" 
                       class="sidebar-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>Laporan</span>
                    </a>
                    @endrole

                    <p class="sidebar-section">Akses Cepat</p>
                    <a href="{{ route('menu.index') }}" target="_blank"
                       class="sidebar-link">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        <span>E-Menu Publik</span>
                    </a>
                    <a href="{{ route('qris.page') }}" target="_blank"
                       class="sidebar-link">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        <span>Halaman QRIS</span>
                    </a>
                </nav>

                <!-- User info bottom -->
                <div class="border-t border-white/10 p-4 shrink-0">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 bg-amber-500/20 rounded-full flex items-center justify-center text-amber-400 font-bold text-sm">
                            {{ strtoupper(substr(Auth::user()->name ?? 'O', 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name ?? 'Owner' }}</p>
                            <p class="text-xs text-gray-500">Administrator</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" title="Logout" class="text-gray-500 hover:text-red-400 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col w-full min-w-0">
                <!-- Top Navbar -->
                <header class="bg-white shadow-sm h-16 flex items-center justify-between px-4 lg:px-6 sticky top-0 z-10">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-primary focus:outline-none p-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>

                    <div class="flex items-center space-x-4">
                        <!-- QRIS badge notification -->
                        @php $qrisBadgeTop = \App\Models\QrisOrder::where('status','payment_uploaded')->count(); @endphp
                        @if($qrisBadgeTop > 0)
                        <a href="{{ route('admin.qris-orders.index') }}" class="relative p-2 text-gray-500 hover:text-amber-600 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            <span class="absolute top-1 right-1 bg-red-500 text-white text-[9px] font-bold w-4 h-4 rounded-full flex items-center justify-center">{{ $qrisBadgeTop }}</span>
                        </a>
                        @endif

                        <span class="hidden sm:block text-sm font-medium text-gray-700">{{ Auth::user()->name ?? 'Owner' }}</span>
                    </div>
                </header>

                <!-- Flash Messages -->
                <div class="px-6 pt-4">
                    @if(session('success'))
                        <div class="mb-4 bg-green-50 border-l-4 border-green-500 text-green-800 px-4 py-3 rounded-lg flex items-start">
                            <svg class="w-5 h-5 mr-3 mt-0.5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="mb-4 bg-red-50 border-l-4 border-red-500 text-red-800 px-4 py-3 rounded-lg flex items-start">
                            <svg class="w-5 h-5 mr-3 mt-0.5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            {{ session('error') }}
                        </div>
                    @endif
                </div>

                <!-- Main Content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-background p-4 lg:p-6">
                    {{ $slot }}
                </main>
            </div>
        </div>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </body>
</html>
