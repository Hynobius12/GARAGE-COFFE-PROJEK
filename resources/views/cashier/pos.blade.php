<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Garage Coffee') }} - POS</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
        .glass-panel { background: rgba(30, 30, 36, 0.7); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border-left: 1px solid rgba(255, 255, 255, 0.05); }
        .product-card { background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.05); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .product-card:hover { border-color: rgba(200, 169, 126, 0.5); transform: translateY(-4px); box-shadow: 0 10px 40px -10px rgba(200, 169, 126, 0.15); background: rgba(255, 255, 255, 0.06); }
        .cart-item { transition: all 0.2s; border: 1px solid rgba(255,255,255,0.03); }
        .cart-item:hover { background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1); }
    </style>
</head>
<body class="font-sans antialiased text-gray-300 h-screen bg-[#0f0f11] overflow-hidden flex flex-col" x-data="posApp()">
    
    <!-- Header -->
    <header class="bg-[#151518] border-b border-white/5 h-16 flex items-center justify-between px-6 shrink-0 z-20 shadow-md">
        <div class="flex items-center space-x-4">
            <div class="w-8 h-8 bg-accent rounded flex items-center justify-center text-black font-bold text-xs shadow-[0_0_15px_rgba(200,169,126,0.3)]">GC</div>
            <h1 class="text-xl font-heading text-white tracking-widest">GARAGE <span class="text-accent">COFFEE</span> <span class="text-sm font-sans tracking-normal text-gray-500 ml-2">| POS SYSTEM</span></h1>
        </div>
        <div class="flex items-center space-x-6">
            @php $qrisBadgeTop = \App\Models\QrisOrder::where('status','payment_uploaded')->count(); @endphp
            <button @click="loadHistory()" class="relative text-gray-400 hover:text-white transition-colors flex items-center bg-white/5 px-3 py-1.5 rounded-full border border-white/10 group">
                <svg class="w-5 h-5 mr-1.5 group-hover:text-accent transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                <span class="text-sm font-medium">Kelola Pesanan</span>
                @if($qrisBadgeTop > 0)
                <span class="absolute -top-1 -right-1 flex h-4 w-4">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500 text-[9px] text-white items-center justify-center font-bold">{{ $qrisBadgeTop }}</span>
                </span>
                @endif
            </button>

            <div class="flex items-center space-x-2 bg-white/5 px-3 py-1.5 rounded-full border border-white/10">
                <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                <span class="text-sm font-medium text-gray-300">{{ Auth::user()->name }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-gray-500 hover:text-red-400 text-sm font-medium transition-colors focus:outline-none flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Keluar
                </button>
            </form>
        </div>
    </header>

    <!-- Main Workspace -->
    <div class="flex-1 flex overflow-hidden relative">
        <!-- Background Pattern -->
        <div class="absolute inset-0 z-0 opacity-[0.02] pointer-events-none" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 32px 32px;"></div>
        
        <!-- Left Section (Products) -->
        <div class="flex-1 flex flex-col w-2/3 relative z-10 min-w-0 min-h-0">
            <!-- Categories Ribbon -->
            <div class="px-6 py-4 shrink-0 flex space-x-3 overflow-x-auto hide-scroll">
                <button @click="activeCategory = 'all'" 
                    :class="activeCategory === 'all' ? 'bg-accent text-black shadow-[0_0_15px_rgba(200,169,126,0.3)] font-semibold' : 'bg-[#1a1a1f] text-gray-400 hover:text-white hover:bg-white/10 border border-white/5'" 
                    class="px-5 py-2.5 rounded-full text-sm whitespace-nowrap transition-all duration-300 transform active:scale-95">
                    Semua Menu
                </button>
                @foreach($categories as $category)
                <button @click="activeCategory = {{ $category->id }}" 
                    :class="activeCategory === '{{ $category->id }}' ? 'bg-accent text-black shadow-[0_0_15px_rgba(200,169,126,0.3)] font-semibold' : 'bg-[#1a1a1f] text-gray-400 hover:text-white hover:bg-white/10 border border-white/5'" 
                    class="px-5 py-2.5 rounded-full text-sm whitespace-nowrap transition-all duration-300 transform active:scale-95">
                    {{ $category->name }}
                </button>
                @endforeach
            </div>

            <!-- Products Grid -->
            <div class="flex-1 overflow-y-auto px-6 pb-6 hide-scroll">
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-5">
                    <template x-for="product in filteredProducts" :key="product.id">
                        <div @click="addToCart(product)" class="product-card rounded-2xl overflow-hidden cursor-pointer relative group flex flex-col h-full">
                            <div class="h-36 bg-[#151518] w-full relative shrink-0 p-4 flex items-center justify-center">
                                <img x-show="product.image" :src="product.image_url" class="absolute inset-0 w-full h-full object-cover mix-blend-luminosity opacity-80 group-hover:mix-blend-normal group-hover:opacity-100 transition-all duration-500">
                                
                                <!-- Icon fallback if no image -->
                                <div x-show="!product.image" class="w-16 h-16 rounded-full bg-white/5 flex items-center justify-center text-accent/50 group-hover:text-accent group-hover:scale-110 transition-all duration-500">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 8h-2.828a2 2 0 01-1.414-.586l-1.121-1.121A2 2 0 0013.229 5.7H10.77a2 2 0 00-1.414.586L8.235 7.414A2 2 0 016.821 8H4a2 2 0 00-2 2v9a2 2 0 002 2h16a2 2 0 002-2v-9a2 2 0 00-2-2zM4 14a2 2 0 012-2h12a2 2 0 012 2v3H4v-3z"></path></svg>
                                </div>
                                
                                <div class="absolute inset-0 bg-accent/20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 backdrop-blur-[2px]">
                                    <div class="bg-black/50 text-white px-4 py-2 rounded-full text-sm font-medium backdrop-blur-md flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                        Tambah
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 flex-1 flex flex-col justify-between bg-gradient-to-t from-[#151518] to-transparent">
                                <div>
                                    <h3 class="font-semibold text-gray-100 leading-tight group-hover:text-accent transition-colors" x-text="product.name"></h3>
                                </div>
                                <div class="mt-3 flex items-center justify-between">
                                    <p class="text-accent font-bold tracking-wide" x-text="formatMoney(product.base_price)"></p>
                                    <span x-show="product.variants && product.variants.length > 0" class="text-[10px] uppercase tracking-wider bg-white/10 text-gray-300 px-2 py-1 rounded-full border border-white/10">Varian</span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Right Section (Cart) -->
        <div class="w-[380px] glass-panel flex flex-col z-20 shadow-2xl relative">
            <!-- Neon Edge Line -->
            <div class="absolute inset-y-0 left-0 w-[1px] bg-gradient-to-b from-transparent via-accent/50 to-transparent"></div>
            
            <!-- Cart Header -->
            <div class="p-5 border-b border-white/5 shrink-0 flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    <h2 class="text-lg font-bold text-white tracking-wide">Current Order</h2>
                </div>
                <button @click="clearCart()" x-show="cart.length > 0" class="text-red-400 hover:text-red-300 text-xs font-medium uppercase tracking-wider bg-red-400/10 px-3 py-1.5 rounded-full transition-colors">Clear All</button>
            </div>

            <!-- Cart Items -->
            <div class="flex-1 overflow-y-auto p-4 space-y-3 hide-scroll">
                <div x-show="cart.length === 0" class="h-full flex flex-col items-center justify-center text-gray-500 space-y-4">
                    <div class="w-20 h-20 rounded-full bg-white/5 flex items-center justify-center border border-white/5">
                        <svg class="w-10 h-10 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <p class="font-medium text-sm">Keranjang masih kosong</p>
                </div>

                <template x-for="(item, index) in cart" :key="index">
                    <div class="cart-item bg-[#1a1a1f]/80 rounded-xl p-3 relative group">
                        <button @click="removeItem(index)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center shadow-lg hover:bg-red-600 transition opacity-0 group-hover:opacity-100 transform scale-75 group-hover:scale-100">
                            <span class="text-xs font-bold leading-none">&times;</span>
                        </button>
                        <div class="flex justify-between items-start mb-2">
                            <div class="pr-2">
                                <h4 class="font-bold text-gray-200 text-sm leading-tight" x-text="item.name"></h4>
                                <span x-show="item.variant_name" class="text-[11px] text-accent block mt-0.5" x-text="'• ' + item.variant_name"></span>
                            </div>
                            <span class="font-bold text-white text-sm shrink-0 bg-white/5 px-2 py-1 rounded" x-text="formatMoney(item.unit_price * item.quantity)"></span>
                        </div>
                        
                        <div class="flex justify-between items-center mt-3">
                            <div class="flex items-center bg-[#151518] rounded-lg border border-white/10 p-0.5">
                                <button @click="updateQty(index, -1)" class="w-7 h-7 flex items-center justify-center text-gray-400 hover:text-white hover:bg-white/10 rounded-md transition-colors">-</button>
                                <span class="w-8 text-center font-bold text-sm text-white" x-text="item.quantity"></span>
                                <button @click="updateQty(index, 1)" class="w-7 h-7 flex items-center justify-center text-gray-400 hover:text-white hover:bg-white/10 rounded-md transition-colors">+</button>
                            </div>
                            <button @click="editNotes(index)" class="text-xs text-gray-400 hover:text-accent flex items-center transition-colors">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg> 
                                <span x-text="item.special_instructions ? 'Edit Notes' : 'Add Note'"></span>
                            </button>
                        </div>
                        <div x-show="item.special_instructions" class="mt-2 text-[11px] text-accent/80 bg-accent/10 px-2 py-1.5 rounded border border-accent/20 italic">
                            <span x-text="item.special_instructions"></span>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Cart Footer (Totals & Checkout) -->
            <div class="border-t border-white/5 p-5 shrink-0 bg-[#151518]/90 backdrop-blur-xl">
                <div class="space-y-2 mb-5">
                    <div class="flex justify-between text-gray-400 text-sm">
                        <span>Subtotal</span>
                        <span class="font-medium text-gray-300" x-text="formatMoney(subtotal)"></span>
                    </div>
                    <div class="flex justify-between text-gray-400 text-sm">
                        <span>Pajak (10%)</span>
                        <span class="font-medium text-gray-300" x-text="formatMoney(tax)"></span>
                    </div>
                    <div class="flex justify-between items-end pt-3 border-t border-white/10 mt-3">
                        <span class="text-gray-300 text-sm uppercase tracking-wider">Total</span>
                        <span class="font-bold text-2xl text-accent tracking-tight" x-text="formatMoney(total)"></span>
                    </div>
                </div>

                <div class="flex space-x-3 mb-4">
                    <button @click="paymentMethod = 'cash'" 
                        :class="paymentMethod === 'cash' ? 'bg-accent text-black font-bold shadow-[0_0_10px_rgba(200,169,126,0.3)]' : 'bg-white/5 text-gray-300 hover:bg-white/10 border border-white/10'" 
                        class="flex-1 py-3 rounded-xl text-sm transition-all flex flex-col items-center justify-center">
                        <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Tunai
                    </button>
                    <button @click="paymentMethod = 'qris'" 
                        :class="paymentMethod === 'qris' ? 'bg-accent text-black font-bold shadow-[0_0_10px_rgba(200,169,126,0.3)]' : 'bg-white/5 text-gray-300 hover:bg-white/10 border border-white/10'" 
                        class="flex-1 py-3 rounded-xl text-sm transition-all flex flex-col items-center justify-center">
                        <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        QRIS
                    </button>
                </div>

                <div class="mb-4">
                    <input x-model="customerName" type="text" placeholder="Nama Pelanggan (Opsional)" class="w-full text-sm bg-black/40 border border-white/10 text-white rounded-xl px-4 py-3 focus:ring-accent focus:border-accent placeholder-gray-500 transition-colors">
                </div>

                <button @click="processCheckout()" :disabled="cart.length === 0 || isProcessing" 
                    :class="cart.length === 0 || isProcessing ? 'bg-white/10 text-gray-500 cursor-not-allowed' : 'bg-gradient-to-r from-[#d4b992] to-[#c8a97e] text-black shadow-[0_4px_20px_rgba(200,169,126,0.4)] hover:shadow-[0_6px_25px_rgba(200,169,126,0.6)] hover:-translate-y-0.5'" 
                    class="w-full py-4 rounded-xl font-bold text-sm uppercase tracking-widest transition-all duration-300 flex items-center justify-center">
                    <svg x-show="isProcessing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-text="isProcessing ? 'MEMPROSES...' : 'BAYAR SEKARANG'"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Variant Modal (Dark/Glass) -->
    <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div x-show="isModalOpen" x-transition.opacity.duration.300ms @click="closeModal()" class="absolute inset-0 bg-black/80 backdrop-blur-sm"></div>

        <!-- Modal Dialog -->
        <div x-show="isModalOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="relative bg-[#1a1a1f] border border-white/10 rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform">
            
            <template x-if="selectedProduct">
                <div>
                    <!-- Header -->
                    <div class="bg-[#151518] px-6 py-5 border-b border-white/5 flex justify-between items-start">
                        <div>
                            <h3 class="text-xl font-bold text-white tracking-wide" x-text="selectedProduct.name"></h3>
                            <p class="text-accent text-sm mt-1 font-medium" x-text="formatMoney(selectedProduct.base_price) + ' (Base)'"></p>
                        </div>
                        <button @click="closeModal()" class="text-gray-500 hover:text-white bg-white/5 hover:bg-red-500/20 w-8 h-8 rounded-full flex items-center justify-center transition-colors">&times;</button>
                    </div>
                    
                    <div class="px-6 py-5">
                        <h4 class="text-xs uppercase tracking-widest font-semibold text-gray-500 mb-3">Pilih Varian (Wajib)</h4>
                        <div class="space-y-3 mb-6">
                            <!-- Standard Variant -->
                            <label class="flex items-center p-4 border rounded-xl cursor-pointer transition-all"
                                   :class="selectedVariant === null ? 'border-accent bg-accent/10 shadow-[0_0_15px_rgba(200,169,126,0.15)]' : 'border-white/10 bg-black/20 hover:border-white/20 hover:bg-white/5'">
                                <input type="radio" name="variant_pos" :value="null" x-model="selectedVariant" class="text-accent focus:ring-accent bg-transparent border-gray-600 h-4 w-4">
                                <div class="ml-4 flex-1 flex justify-between items-center">
                                    <span class="text-sm font-bold" :class="selectedVariant === null ? 'text-white' : 'text-gray-300'">Standar</span>
                                    <span class="text-sm text-gray-400" x-text="formatMoney(selectedProduct.base_price)"></span>
                                </div>
                            </label>

                            <!-- Custom Variants -->
                            <template x-for="variant in selectedProduct.variants" :key="variant.id">
                                <label x-show="variant.is_available" class="flex items-center p-4 border rounded-xl cursor-pointer transition-all"
                                       :class="selectedVariant === variant.id ? 'border-accent bg-accent/10 shadow-[0_0_15px_rgba(200,169,126,0.15)]' : 'border-white/10 bg-black/20 hover:border-white/20 hover:bg-white/5'">
                                    <input type="radio" name="variant_pos" :value="variant.id" x-model="selectedVariant" class="text-accent focus:ring-accent bg-transparent border-gray-600 h-4 w-4">
                                    <div class="ml-4 flex-1 flex justify-between items-center">
                                        <span class="text-sm font-bold" :class="selectedVariant === variant.id ? 'text-white' : 'text-gray-300'" x-text="variant.name"></span>
                                        <span class="text-sm text-gray-400" x-text="formatMoney(parseFloat(selectedProduct.base_price) + parseFloat(variant.additional_price))"></span>
                                    </div>
                                </label>
                            </template>
                        </div>

                        <!-- Special Instructions -->
                        <div class="mb-6">
                            <h4 class="text-xs uppercase tracking-widest font-semibold text-gray-500 mb-3">Instruksi Khusus (Opsional)</h4>
                            <textarea x-model="specialInstructions" rows="2" class="w-full text-sm bg-black/40 border border-white/10 text-white rounded-xl focus:ring-accent focus:border-accent p-4 placeholder-gray-600 transition-colors" placeholder="Contoh: Less ice, extra sugar..."></textarea>
                        </div>

                        <!-- Add Button -->
                        <button @click="confirmAddToCart()" class="w-full bg-accent hover:bg-[#d4b992] text-black py-4 px-4 rounded-xl font-bold text-sm uppercase tracking-widest transition-colors shadow-[0_0_20px_rgba(200,169,126,0.3)]">
                            Tambahkan ke Pesanan
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Kelola Pesanan Modal -->
    <div x-show="isHistoryModalOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div x-show="isHistoryModalOpen" x-transition.opacity.duration.300ms @click="closeHistory()" class="absolute inset-0 bg-black/80 backdrop-blur-sm"></div>
        <div x-show="isHistoryModalOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="relative bg-[#1a1a1f] border border-white/10 rounded-2xl shadow-2xl w-full max-w-5xl h-[85vh] flex flex-col overflow-hidden transform">
            
            <div class="bg-[#151518] px-6 py-5 border-b border-white/5 flex justify-between items-center shrink-0">
                <div>
                    <h3 class="text-xl font-bold text-white tracking-wide">Kelola Pesanan Hari Ini</h3>
                    <p class="text-xs text-gray-500 mt-1">Atur status pesanan langsung dari sini</p>
                </div>
                <button @click="closeHistory()" class="text-gray-500 hover:text-white bg-white/5 hover:bg-red-500/20 w-8 h-8 rounded-full flex items-center justify-center transition-colors">&times;</button>
            </div>
            
            <div class="p-6 flex-1 overflow-y-auto">
                <div x-show="isLoadingHistory" class="flex justify-center items-center h-32">
                    <svg class="animate-spin h-8 w-8 text-accent" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </div>
                
                <table x-show="!isLoadingHistory" class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-gray-500 text-sm border-b border-white/10">
                            <th class="py-3 pr-4 font-semibold uppercase tracking-wider">No. Order</th>
                            <th class="py-3 pr-4 font-semibold uppercase tracking-wider">Pelanggan</th>
                            <th class="py-3 pr-4 font-semibold uppercase tracking-wider">Waktu</th>
                            <th class="py-3 pr-4 font-semibold uppercase tracking-wider">Total</th>
                            <th class="py-3 pr-4 font-semibold uppercase tracking-wider">Status</th>
                            <th class="py-3 font-semibold uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="order in orderHistory" :key="order.id + '-' + order.type">
                            <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                                <td class="py-3 pr-4 text-white font-mono font-bold text-sm">
                                    <span x-text="order.order_number"></span>
                                    <span x-show="order.type === 'web'" class="ml-1 px-1.5 py-0.5 bg-indigo-500/20 text-indigo-400 text-[10px] rounded uppercase border border-indigo-500/30">WEB</span>
                                </td>
                                <td class="py-3 pr-4">
                                    <span class="text-gray-300 text-sm" x-text="order.customer_name || '-'"></span>
                                    <span x-show="order.customer_table" class="block text-gray-500 text-xs" x-text="'Meja: ' + order.customer_table"></span>
                                </td>
                                <td class="py-3 pr-4 text-gray-400 text-sm" x-text="new Date(order.created_at).toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'})"></td>
                                <td class="py-3 pr-4 text-accent font-bold text-sm" x-text="formatMoney(order.total_amount)"></td>
                                <td class="py-3 pr-4">
                                    <span class="px-2 py-1 rounded text-xs font-bold uppercase tracking-wider"
                                          :class="{
                                              'bg-yellow-500/20 text-yellow-500': order.status === 'pending' || order.status === 'pending_payment',
                                              'bg-orange-500/20 text-orange-400': order.status === 'payment_uploaded',
                                              'bg-purple-500/20 text-purple-400': order.status === 'confirmed',
                                              'bg-blue-500/20 text-blue-400': order.status === 'processing',
                                              'bg-emerald-500/20 text-emerald-400': order.status === 'completed',
                                              'bg-red-500/20 text-red-400': order.status === 'cancelled'
                                          }" x-text="order.status.replaceAll('_', ' ')">
                                    </span>
                                </td>
                                <td class="py-3">
                                    <div class="flex flex-wrap gap-1">
                                        <!-- Bukti Bayar (web only, payment_uploaded) -->
                                        <button x-show="order.type === 'web' && order.payment_proof_url && order.status === 'payment_uploaded'"
                                                @click.stop="proofImageUrl = order.payment_proof_url; isProofModalOpen = true"
                                                class="px-2 py-1 bg-orange-500/20 hover:bg-orange-500/40 text-orange-400 text-xs rounded-lg font-bold transition-colors">
                                            Cek Bukti
                                        </button>
                                        <!-- Konfirmasi (web only, payment_uploaded) -->
                                        <button x-show="order.type === 'web' && order.status === 'payment_uploaded'"
                                                @click="updateStatus(order, 'confirm')"
                                                class="px-2 py-1 bg-purple-500/20 hover:bg-purple-500/40 text-purple-400 text-xs rounded-lg font-bold transition-colors">
                                            Konfirmasi
                                        </button>
                                        <!-- Proses (pos: pending | web: confirmed) -->
                                        <button x-show="(order.type === 'pos' && order.status === 'pending') || (order.type === 'web' && order.status === 'confirmed')"
                                                @click="updateStatus(order, 'process')"
                                                class="px-2 py-1 bg-blue-500/20 hover:bg-blue-500/40 text-blue-400 text-xs rounded-lg font-bold transition-colors">
                                            Proses
                                        </button>
                                        <!-- Selesai (processing) -->
                                        <button x-show="order.status === 'processing'"
                                                @click="updateStatus(order, 'complete')"
                                                class="px-2 py-1 bg-emerald-500/20 hover:bg-emerald-500/40 text-emerald-400 text-xs rounded-lg font-bold transition-colors">
                                            Selesai
                                        </button>
                                        <!-- Batal -->
                                        <button x-show="order.status !== 'completed' && order.status !== 'cancelled'"
                                                @click="updateStatus(order, 'cancel')"
                                                class="px-2 py-1 bg-red-500/10 hover:bg-red-500/30 text-red-500/70 hover:text-red-400 text-xs rounded-lg font-bold transition-colors">
                                            Batal
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="!isLoadingHistory && orderHistory.length === 0">
                            <td colspan="6" class="py-8 text-center text-gray-500">Belum ada pesanan hari ini.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Payment Proof Modal -->
    <div x-show="isProofModalOpen" style="display:none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div @click="isProofModalOpen = false; proofImageUrl = ''" class="absolute inset-0 bg-black/90 backdrop-blur-sm"></div>
        <div class="relative bg-[#1a1a1f] border border-white/10 rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100">
            <div class="bg-[#151518] px-5 py-4 border-b border-white/5 flex justify-between items-center">
                <h4 class="font-bold text-white">Bukti Transfer</h4>
                <button @click="isProofModalOpen = false" class="text-gray-500 hover:text-white text-xl leading-none">&times;</button>
            </div>
            <div class="p-4">
                <template x-if="proofImageUrl">
                    <img :src="proofImageUrl" :key="proofImageUrl" alt="Bukti Transfer" class="w-full rounded-xl object-contain max-h-96">
                </template>
            </div>
        </div>
    </div>

    <!-- QRIS Payment Modal -->
    <div x-show="isQrisModalOpen" style="display: none;"  class="fixed inset-0 z-50 flex items-center justify-center p-4 z-[999]">
        <div x-show="isQrisModalOpen" x-transition.opacity.duration.300ms @click="closeQris()" class="absolute inset-0 bg-black/80 backdrop-blur-sm"></div>
        <div x-show="isQrisModalOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="relative bg-[#1a1a1f] border border-white/10 rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden transform">
            
            <div class="bg-gradient-to-r from-accent to-[#d4b992] px-6 py-4 text-center">
                <h3 class="text-lg font-bold text-black tracking-wide">PEMBAYARAN QRIS</h3>
                <p class="text-black/70 text-xs">Arahkan pelanggan untuk scan kode ini</p>
            </div>
            
            <div class="p-8 flex flex-col items-center bg-white">
                <div class="bg-gray-50 border-4 border-amber-200 rounded-2xl p-3 shadow-inner">
                    <img src="{{ asset('images/qris.jpg') }}" alt="QRIS Garage Coffee" class="w-64 h-64 object-contain rounded-xl">
                </div>
                <h2 class="mt-6 text-3xl font-black text-gray-900" x-text="formatMoney(qrisAmount)"></h2>
            </div>

            <div class="p-4 bg-[#151518] flex justify-center border-t border-white/5">
                <button @click="closeQris()" class="w-full bg-white/10 hover:bg-white/20 text-white font-bold py-3 rounded-xl transition-colors text-sm uppercase tracking-widest">
                    Selesai (Tutup)
                </button>
            </div>
        </div>
    </div>

    <!-- Receipt Print Template (Hidden) -->
    <div id="print-area" class="hidden">
        <div style="width: 80mm; font-family: monospace; font-size: 12px; margin: 0 auto; color: #000; background: #fff; padding: 10px;">
            <div style="text-align: center; margin-bottom: 10px;">
                <h1 style="font-size: 18px; margin: 0; padding: 0; text-transform: uppercase; font-weight: bold;">GARAGE COFFEE</h1>
                <p style="margin: 2px 0;">Industrial Crafted Coffee</p>
                <p style="margin: 2px 0;">================================</p>
            </div>
            
            <div style="margin-bottom: 10px;">
                <p style="margin: 2px 0;">No Order : <span id="print-order-number"></span></p>
                <p style="margin: 2px 0;">Kasir    : {{ Auth::user()->name }}</p>
                <p style="margin: 2px 0;">Waktu    : <span id="print-time"></span></p>
                <p style="margin: 2px 0;">Pelanggan: <span id="print-customer"></span></p>
                <p style="margin: 2px 0;">--------------------------------</p>
            </div>

            <div id="print-items" style="margin-bottom: 10px;">
                <!-- items injected via JS -->
            </div>

            <div style="margin-bottom: 10px;">
                <p style="margin: 2px 0;">--------------------------------</p>
                <div style="display: flex; justify-content: space-between;">
                    <span>Subtotal</span>
                    <span id="print-subtotal"></span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span>Pajak (10%)</span>
                    <span id="print-tax"></span>
                </div>
                <div style="display: flex; justify-content: space-between; font-weight: bold; margin-top: 5px; font-size: 14px;">
                    <span>TOTAL</span>
                    <span id="print-total"></span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-top: 5px;">
                    <span>Metode: <span id="print-method" style="text-transform: uppercase;"></span></span>
                </div>
            </div>

            <div style="text-align: center; margin-top: 15px;">
                <p style="margin: 2px 0;">================================</p>
                <p style="margin: 2px 0; font-weight: bold;">Terima Kasih</p>
                <p style="margin: 2px 0;">Silakan Nikmati Pesanan Anda</p>
                <p style="margin: 2px 0;">================================</p>
            </div>
        </div>
    </div>

    <style>
        @media print {
            body * { display: none !important; }
            #print-area, #print-area * { display: block !important; }
            #print-area { position: absolute; left: 0; top: 0; width: 100%; margin: 0; padding: 0; background: white; }
        }
    </style>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        function posApp() {
            const productsData = @json($products);
            const products = productsData.map(p => ({
                ...p,
                image_url: p.image ? '{{ Storage::url('') }}' + p.image : null
            }));

            return {
                products: products,
                activeCategory: 'all',
                cart: [],
                paymentMethod: 'cash',
                customerName: '',
                isProcessing: false,
                isModalOpen: false,
                selectedProduct: null,
                selectedVariant: null,
                specialInstructions: '',
                isHistoryModalOpen: false,
                orderHistory: [],
                isLoadingHistory: false,
                isQrisModalOpen: false,
                qrisAmount: 0,
                isProofModalOpen: false,
                proofImageUrl: '',

                get filteredProducts() {
                    if (this.activeCategory === 'all') return this.products;
                    return this.products.filter(p => p.category_id === this.activeCategory);
                },

                get subtotal() {
                    return this.cart.reduce((sum, item) => sum + (item.unit_price * item.quantity), 0);
                },

                get tax() { return Math.round(this.subtotal * 0.10); },
                get total() { return this.subtotal + this.tax; },

                addToCart(product) {
                    if (product.variants && product.variants.length > 0) {
                        this.selectedProduct = product;
                        this.selectedVariant = null;
                        this.specialInstructions = '';
                        this.isModalOpen = true;
                    } else {
                        this.pushToCart(product, null, '', 1);
                    }
                },

                confirmAddToCart() {
                    this.pushToCart(this.selectedProduct, this.selectedVariant, this.specialInstructions, 1);
                    this.closeModal();
                },

                pushToCart(product, variantId, notes, quantitySpan) {
                    let price = parseFloat(product.base_price);
                    let variantName = null;
                    if (variantId) {
                        const variant = product.variants.find(v => v.id === variantId);
                        if (variant) {
                            price += parseFloat(variant.additional_price);
                            variantName = variant.name;
                        }
                    }
                    const existingIndex = this.cart.findIndex(i => 
                        i.product_id === product.id && i.product_variant_id === variantId && i.special_instructions === notes
                    );
                    if (existingIndex > -1) {
                        this.cart[existingIndex].quantity += quantitySpan;
                    } else {
                        this.cart.push({
                            product_id: product.id, product_variant_id: variantId, name: product.name,
                            variant_name: variantName, unit_price: price, quantity: quantitySpan, special_instructions: notes || ''
                        });
                    }
                },

                closeModal() {
                    this.isModalOpen = false;
                    setTimeout(() => { this.selectedProduct = null; }, 300);
                },

                updateQty(index, change) {
                    const newQty = this.cart[index].quantity + change;
                    if (newQty > 0) this.cart[index].quantity = newQty;
                    else if (newQty === 0) this.removeItem(index);
                },

                removeItem(index) { this.cart.splice(index, 1); },

                clearCart() {
                    if(confirm("Batalkan pesanan ini?")) {
                        this.cart = []; this.customerName = ''; this.paymentMethod = 'cash';
                    }
                },

                editNotes(index) {
                    const note = prompt("Catatan (opsional):", this.cart[index].special_instructions || '');
                    if (note !== null) this.cart[index].special_instructions = note;
                },

                formatMoney(amount) { return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(amount); },

                printReceipt(order) {
                    document.getElementById('print-order-number').textContent = order.order_number;
                    document.getElementById('print-time').textContent = new Date().toLocaleString('id-ID');
                    document.getElementById('print-customer').textContent = order.customer_name || '-';
                    document.getElementById('print-subtotal').textContent = this.formatMoney(order.subtotal);
                    document.getElementById('print-tax').textContent = this.formatMoney(order.tax);
                    document.getElementById('print-total').textContent = this.formatMoney(order.total_amount);
                    document.getElementById('print-method').textContent = order.payment_method;

                    let itemsHtml = '';
                    this.cart.forEach(item => {
                        let name = item.name;
                        if(item.variant_name) name += ' (' + item.variant_name + ')';
                        itemsHtml += `<div style="margin-bottom: 4px;"><div style="display: flex; justify-content: space-between;"><span>${name}</span></div><div style="display: flex; justify-content: space-between; color: #555;"><span>${item.quantity} x ${this.formatMoney(item.unit_price)}</span><span>${this.formatMoney(item.quantity * item.unit_price)}</span></div></div>`;
                    });
                    document.getElementById('print-items').innerHTML = itemsHtml;
                    window.print();
                },

                async loadHistory() {
                    this.isHistoryModalOpen = true;
                    this.isLoadingHistory = true;
                    try {
                        const response = await fetch("{{ route('cashier.pos.history') }}");
                        const result = await response.json();
                        if(result.success) {
                            this.orderHistory = result.orders;
                        }
                    } catch(e) {
                        console.error(e);
                    } finally {
                        this.isLoadingHistory = false;
                    }
                },

                closeHistory() {
                    this.isHistoryModalOpen = false;
                },

                viewProof(order) {
                    this.proofImageUrl = order.payment_proof_url;
                    this.isProofModalOpen = true;
                },

                async updateStatus(order, action) {
                    const url = `/cashier/pos/orders/${order.type}/${order.id}/status`;
                    try {
                        const response = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ action })
                        });
                        const result = await response.json();
                        if (result.success) {
                            const idx = this.orderHistory.findIndex(o => o.id === order.id && o.type === order.type);
                            if (idx > -1) this.orderHistory[idx].status = result.status;
                        } else {
                            alert('Gagal: ' + (result.message || 'Terjadi kesalahan.'));
                        }
                    } catch(e) {
                        alert('Terjadi kesalahan jaringan.');
                    }
                },

                closeQris() {
                    this.isQrisModalOpen = false;
                    this.cart = []; this.customerName = '';
                },

                async processCheckout() {
                    if (this.cart.length === 0) return;
                    this.isProcessing = true;
                    
                    try {
                        const response = await fetch("{{ route('cashier.pos.checkout') }}", {
                            method: "POST",
                            headers: { "Content-Type": "application/json", "Accept": "application/json" },
                            body: JSON.stringify({
                                items: this.cart, payment_method: this.paymentMethod, customer_name: this.customerName,
                                subtotal: this.subtotal, tax: this.tax, discount: 0, total_amount: this.total,
                                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            })
                        });

                        const result = await response.json();

                        if (response.ok && result.success) {
                            alert("SUKSES!\nNomor Pesanan: " + result.order.order_number);
                            this.printReceipt(result.order);
                            
                            if (this.paymentMethod === 'qris') {
                                this.qrisAmount = this.total;
                                this.isQrisModalOpen = true;
                                // The cart will be cleared when closeQris is called
                            } else {
                                this.cart = []; this.customerName = '';
                            }
                        } else {
                            alert("Gagal: " + (result.message || "Kesalahan server."));
                        }
                    } catch (error) {
                        alert("Terjadi kesalahan jaringan.");
                    } finally {
                        this.isProcessing = false;
                    }
                }
            }
        }
    </script>
</body>
</html>
