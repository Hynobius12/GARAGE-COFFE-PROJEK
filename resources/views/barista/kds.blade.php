<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="refresh" content="15">
    <title>KDS - Garage Coffee</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,600,800&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .ticket-pending { border: 2px solid #ef4444; box-shadow: 0 0 25px rgba(239, 68, 68, 0.2), inset 0 0 20px rgba(239, 68, 68, 0.05); }
        .ticket-processing { border: 2px solid #eab308; box-shadow: 0 0 25px rgba(234, 179, 8, 0.2), inset 0 0 20px rgba(234, 179, 8, 0.05); }
        .bg-pattern { background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.05) 1px, transparent 0); background-size: 32px 32px; }
    </style>
</head>
<body class="font-sans antialiased text-gray-200 bg-[#0a0a0a] h-screen overflow-hidden flex flex-col relative">
    
    <!-- Background Pattern -->
    <div class="absolute inset-0 z-0 bg-pattern pointer-events-none"></div>

    <!-- Navbar -->
    <header class="bg-[#151518]/90 backdrop-blur-md text-white h-[72px] flex items-center justify-between px-8 shrink-0 z-10 border-b border-white/10 shadow-2xl">
        <div class="flex items-center space-x-5">
            <div class="w-10 h-10 bg-accent rounded-lg flex items-center justify-center text-black font-black text-sm shadow-[0_0_15px_rgba(200,169,126,0.4)]">KDS</div>
            <div>
                <h1 class="text-xl font-heading text-white tracking-widest leading-none">BARISTA STATION</h1>
                <p class="text-xs text-accent mt-1 tracking-widest uppercase font-semibold">Garage Coffee</p>
            </div>
            <div class="bg-[#1a1a1f] border border-white/10 text-gray-400 text-xs px-3 py-1.5 rounded-full ml-4 flex items-center">
                <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse mr-2"></div>
                Auto Refresh 15s
            </div>
        </div>
        <div class="flex items-center space-x-6">
            @php $qrisBadgeTop = \App\Models\QrisOrder::where('status','confirmed')->count(); @endphp
            <a href="{{ route('admin.qris-orders.index') }}" target="_blank" class="relative text-gray-400 hover:text-white transition-colors flex items-center bg-white/5 px-4 py-2 rounded-lg border border-white/10 group font-bold tracking-wider text-sm">
                <svg class="w-5 h-5 mr-2 group-hover:text-accent transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                <span>PESANAN WEB</span>
                @if($qrisBadgeTop > 0)
                <span class="absolute -top-1.5 -right-1.5 flex h-5 w-5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-5 w-5 bg-red-500 text-[10px] text-white items-center justify-center font-black">{{ $qrisBadgeTop }}</span>
                </span>
                @endif
            </a>

            <span class="font-bold text-gray-300 tracking-wide ml-4">{{ mb_strtoupper(Auth::user()->name) }}</span>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="bg-red-500/10 hover:bg-red-500/20 text-red-500 border border-red-500/20 hover:border-red-500/50 px-4 py-2 rounded-lg text-sm font-bold tracking-wider transition-all">EXIT KDS</button>
            </form>
        </div>
    </header>

    <!-- Main Content -->
    <div class="flex-1 overflow-x-auto overflow-y-hidden p-8 flex space-x-6 items-start z-10 hide-scroll">
        
        @forelse($orders as $order)
            <div class="w-[340px] shrink-0 bg-[#151518] rounded-2xl overflow-hidden flex flex-col max-h-[85vh] {{ $order['status'] === 'pending' ? 'ticket-pending' : 'ticket-processing' }} transform transition-all hover:-translate-y-1">
                
                <!-- Ticket Header -->
                <div class="p-5 {{ $order['status'] === 'pending' ? 'bg-red-500/10 border-red-500/30' : 'bg-yellow-500/10 border-yellow-500/30' }} text-center relative border-b shrink-0">
                    <h2 class="text-3xl font-black tracking-widest {{ $order['status'] === 'pending' ? 'text-red-400' : 'text-yellow-400' }} drop-shadow-md">
                        #{{ substr($order['order_number'], -4) }}
                    </h2>
                    <p class="text-[11px] font-bold uppercase tracking-widest mt-2 {{ $order['status'] === 'pending' ? 'text-red-300' : 'text-yellow-300' }}">
                        {{ $order['status'] === 'pending' ? 'Menunggu Diproses' : 'Sedang Disiapkan' }}
                    </p>
                    <div class="absolute top-3 right-3 bg-black/50 px-2 py-1 rounded text-xs font-mono {{ $order['status'] === 'pending' ? 'text-red-400' : 'text-yellow-400' }}">
                        {{ \Carbon\Carbon::parse($order['created_at'])->format('H:i') }}
                    </div>
                    @if($order['type'] === 'web')
                        <div class="absolute top-3 left-3 bg-indigo-500/20 border border-indigo-500/50 px-2 py-0.5 rounded text-[10px] font-bold text-indigo-400 uppercase tracking-widest">
                            E-MENU
                        </div>
                    @endif
                </div>
                
                @if($order['customer_name'])
                    <div class="bg-black/40 px-5 py-3 border-b border-white/5 text-sm font-semibold flex justify-between shrink-0">
                        <span class="text-gray-500 uppercase tracking-wider text-xs">Customer</span>
                        <span class="text-white uppercase tracking-wider">{{ $order['customer_name'] }}</span>
                    </div>
                @endif

                <!-- Items List -->
                <div class="p-5 flex-1 overflow-y-auto bg-transparent hide-scroll">
                    <ul class="space-y-4">
                        @foreach($order['items'] as $item)
                        <li class="relative pl-5 pb-4 border-b border-white/5 last:border-0 last:pb-0">
                            <div class="absolute left-0 top-2 w-2 h-2 rounded-full {{ $order['status'] === 'pending' ? 'bg-red-500 shadow-[0_0_10px_#ef4444]' : 'bg-yellow-500 shadow-[0_0_10px_#eab308]' }}"></div>
                            <div class="flex justify-between items-start font-bold">
                                <div class="pr-3">
                                    <span class="text-lg text-white leading-tight block">{{ $item['name'] }}</span>
                                </div>
                                <span class="bg-white text-black px-3 py-1 rounded-lg text-xl font-black min-w-[3rem] text-center shadow-lg">x{{ $item['quantity'] }}</span>
                            </div>
                            @if(!empty($item['notes']))
                                <div class="mt-3 bg-red-500/10 border border-red-500/20 p-2.5 rounded-lg flex items-start">
                                    <svg class="w-4 h-4 text-red-400 mt-0.5 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    <p class="text-sm text-red-300 italic font-semibold">{{ $item['notes'] }}</p>
                                </div>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Ticket Footer (Actions) -->
                <div class="p-4 bg-black/50 border-t border-white/10 shrink-0 backdrop-blur-md">
                    @if($order['status'] === 'pending')
                        <form action="{{ $order['type'] === 'pos' ? route('barista.kds.process', $order['id']) : route('admin.qris-orders.process', $order['id']) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-black py-4 rounded-xl shadow-[0_0_20px_rgba(37,99,235,0.4)] transition-all transform hover:scale-[1.02] tracking-widest text-sm uppercase">
                                PROSES PESANAN
                            </button>
                        </form>
                    @elseif($order['status'] === 'processing')
                        <form action="{{ $order['type'] === 'pos' ? route('barista.kds.complete', $order['id']) : route('admin.qris-orders.complete', $order['id']) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-400 hover:to-emerald-500 text-white font-black py-4 rounded-xl shadow-[0_0_20px_rgba(34,197,94,0.4)] transition-all transform hover:scale-[1.02] tracking-widest text-sm uppercase">
                                TANDAI SELESAI
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="w-full h-full flex flex-col items-center justify-center text-gray-500">
                <div class="relative mb-6">
                    <div class="absolute inset-0 bg-accent rounded-full blur-xl opacity-20 animate-pulse"></div>
                    <svg class="w-24 h-24 text-gray-700 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <h2 class="text-2xl font-bold tracking-widest text-gray-400">TIDAK ADA PESANAN AKTIF</h2>
                <p class="mt-2 text-gray-600 font-medium tracking-wide">Sistem akan memuat ulang otomatis...</p>
            </div>
        @endforelse

    </div>
</body>
</html>
