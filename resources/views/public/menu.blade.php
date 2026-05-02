<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Garage Coffee') }} - Premium E-Menu</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
        .glass-panel { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1px solid rgba(255, 255, 255, 0.05); }
        .glass-card { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.08); box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1); }
        .glass-card:hover { border-color: rgba(200, 169, 126, 0.4); box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3); }
    </style>
</head>
<body class="font-sans antialiased text-gray-200 min-h-screen pb-10 bg-gradient-to-br from-[#0f0f11] via-[#1a1816] to-[#0a0a0a]" x-data="menuApp()">
    
    <!-- Header Hero -->
    <header class="relative overflow-hidden pt-24 pb-16">
        <div class="absolute inset-0 z-0">
            <img src="/images/hero.png" class="w-full h-full object-cover opacity-30 select-none pointer-events-none" alt="Garage Coffee Environment">
            <div class="absolute inset-0 bg-gradient-to-b from-black/80 via-black/40 to-[#0f0f11]"></div>
        </div>
        <div class="max-w-5xl mx-auto px-6 text-center relative z-10">
            <h1 class="text-5xl md:text-7xl font-heading text-transparent bg-clip-text bg-gradient-to-r from-accent via-[#e6cda6] to-accent font-bold tracking-wider mb-4 drop-shadow-lg">GARAGE COFFEE</h1>
            <p class="text-gray-300 text-base md:text-lg font-light tracking-wide max-w-2xl mx-auto opacity-90 leading-relaxed">Industrial crafted coffee, meticulously brewed for the bold. Explore our signature beans and bites below.</p>
        </div>
    </header>

    <!-- Categories Nav -->
    <div class="sticky top-0 z-30 glass-panel shadow-2xl">
        <div class="max-w-5xl mx-auto px-4 py-4 flex space-x-3 overflow-x-auto snap-x hide-scroll">
            <button @click="activeCategory = 'all'" 
                :class="activeCategory === 'all' ? 'bg-accent text-gray-900 scale-105 font-bold shadow-[0_0_15px_rgba(200,169,126,0.5)]' : 'bg-transparent text-gray-300 hover:text-accent hover:bg-white/5 border border-gray-600/50'" 
                class="snap-start px-6 py-2.5 rounded-full text-sm whitespace-nowrap transition-all duration-300 transform">
                Special Collection
            </button>
            @foreach($categories as $category)
            <button @click="activeCategory = {{ $category->id }}" 
                :class="activeCategory === '{{ $category->id }}' ? 'bg-accent text-gray-900 scale-105 font-bold shadow-[0_0_15px_rgba(200,169,126,0.5)]' : 'bg-transparent text-gray-300 hover:text-accent hover:bg-white/5 border border-gray-600/50'" 
                class="snap-start px-6 py-2.5 rounded-full text-sm whitespace-nowrap transition-all duration-300 transform">
                {{ $category->name }}
            </button>
            @endforeach
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="max-w-5xl mx-auto px-6 mt-12 relative z-10">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <template x-for="product in filteredProducts" :key="product.id">
                <div @click="openModal(product)" class="glass-card rounded-2xl overflow-hidden cursor-pointer transition-all duration-500 transform group relative flex flex-row sm:flex-col items-center sm:items-start h-[120px] sm:h-auto">
                    
                    <!-- Out of Stock Overlay -->
                    <div x-show="!product.is_available" class="absolute inset-0 bg-black/60 z-20 flex items-center justify-center backdrop-blur-sm">
                        <span class="bg-red-500/90 text-white font-bold px-4 py-1.5 rounded-full text-xs tracking-widest uppercase shadow-[0_0_15px_rgba(239,68,68,0.5)]">Sold Out</span>
                    </div>

                    <!-- Image Section -->
                    <div class="h-full w-32 sm:w-full sm:h-56 shrink-0 bg-black/40 relative overflow-hidden">
                        <img x-show="product.image_url" :src="product.image_url" :alt="product.name" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 opacity-90 group-hover:opacity-100">
                        <div x-show="!product.image_url" class="absolute inset-0 flex items-center justify-center text-gray-600">
                            <svg class="w-10 h-10 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        
                        <!-- Gradient Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-[#141416] via-transparent to-transparent opacity-80 sm:block hidden"></div>
                        
                        <div x-show="product.is_featured" class="absolute top-3 right-3 bg-accent/90 text-gray-900 text-[10px] font-bold px-2.5 py-1 rounded shadow-[0_0_10px_rgba(200,169,126,0.3)] uppercase tracking-widest hidden sm:block">
                            Signature
                        </div>
                    </div>

                    <!-- Details Section -->
                    <div class="p-4 sm:p-5 flex-1 w-full flex flex-col justify-between sm:relative absolute left-32 sm:left-0 right-0 top-0 bottom-0">
                        <div>
                            <div class="flex justify-between items-start">
                                <h3 class="font-heading font-bold text-lg text-white group-hover:text-accent transition-colors leading-tight" x-text="product.name"></h3>
                                <div x-show="product.is_featured" class="sm:hidden text-accent drop-shadow-[0_0_5px_rgba(200,169,126,0.5)]">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                </div>
                            </div>
                            <p class="text-sm text-gray-400 mt-1.5 line-clamp-2 font-light" x-text="product.description"></p>
                        </div>
                        
                        <div class="mt-4 flex flex-col sm:flex-row items-start sm:items-center justify-between">
                            <span class="font-bold text-accent text-[17px] tracking-wide" x-text="formatMoney(product.base_price)"></span>
                        </div>
                    </div>
                </div>
            </template>
        </div>
        
        <!-- Empty State -->
        <div x-show="filteredProducts.length === 0" style="display: none;" class="py-20 text-center text-gray-500 glass-card rounded-3xl mt-8">
            <svg class="w-20 h-20 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            <p class="text-lg font-light tracking-wide">Looks like we're empty here.</p>
        </div>
    </div>

    <!-- Product Detail Modal (Glassmorphic) -->
    <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end sm:items-center justify-center min-h-screen pt-4 px-4 pb-0 sm:pb-20 text-center sm:block sm:p-0">
            
            <!-- Backdrop -->
            <div x-show="isModalOpen" 
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 @click="closeModal()" class="fixed inset-0 bg-black/80 transition-opacity backdrop-blur-md" aria-hidden="true"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal Content -->
            <div x-show="isModalOpen" 
                 x-transition:enter="ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200 transform" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-[#121212] border border-gray-800 rounded-t-[32px] sm:rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle w-full sm:max-w-md relative">
                
                <template x-if="selectedProduct">
                    <div class="flex flex-col h-full max-h-[85vh] sm:max-h-[auto]">
                        <!-- Image Area -->
                        <div class="h-64 sm:h-72 w-full bg-black relative shrink-0">
                            <img x-show="selectedProduct.image_url" :src="selectedProduct.image_url" class="absolute inset-0 w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-[#121212] via-transparent to-black/30"></div>
                            
                            <button @click="closeModal()" class="absolute top-4 right-4 bg-black/40 backdrop-blur border border-white/10 text-white hover:bg-black/60 rounded-full w-10 h-10 flex items-center justify-center transition-all shadow-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <!-- Content Area -->
                        <div class="px-6 py-5 overflow-y-auto hide-scroll flex-1">
                            <h3 class="text-3xl font-heading font-bold text-white tracking-wide" x-text="selectedProduct.name"></h3>
                            <p class="text-gray-400 mt-2 text-sm leading-relaxed font-light" x-text="selectedProduct.description"></p>
                            
                            <!-- Variants -->
                            <template x-if="selectedProduct.variants && selectedProduct.variants.length > 0">
                                <div class="mt-8">
                                    <h4 class="text-xs font-bold tracking-widest text-gray-500 uppercase mb-3">Pilih Varian</h4>
                                    <div class="space-y-3">
                                        <label class="flex items-center p-4 border rounded-xl cursor-pointer transition-all duration-200 select-none overflow-hidden relative"
                                               :class="selectedVariant === null ? 'border-accent bg-accent/5' : 'border-gray-800 bg-black/20 hover:border-gray-600'">
                                            <input type="radio" name="variant" :value="null" x-model="selectedVariant" class="focus:ring-accent text-accent h-4 w-4 bg-gray-900 border-gray-700">
                                            <div class="ml-4 flex-1 flex justify-between items-center">
                                                <span class="text-sm font-medium" :class="selectedVariant === null ? 'text-accent' : 'text-gray-300'">Standard (Base)</span>
                                                <span class="text-sm tracking-wide text-gray-400" x-text="formatMoney(selectedProduct.base_price)"></span>
                                            </div>
                                        </label>

                                        <template x-for="variant in selectedProduct.variants" :key="variant.id">
                                            <label x-show="variant.is_available" class="flex items-center p-4 border rounded-xl cursor-pointer transition-all duration-200 select-none relative"
                                                   :class="selectedVariant === variant.id ? 'border-accent bg-accent/5' : 'border-gray-800 bg-black/20 hover:border-gray-600'">
                                                <input type="radio" name="variant" :value="variant.id" x-model="selectedVariant" class="focus:ring-accent text-accent h-4 w-4 bg-gray-900 border-gray-700">
                                                <div class="ml-4 flex-1 flex justify-between items-center">
                                                    <span class="text-sm font-medium" :class="selectedVariant === variant.id ? 'text-accent' : 'text-gray-300'" x-text="variant.name"></span>
                                                    <span class="text-sm tracking-wide text-gray-400" x-text="formatMoney(parseFloat(selectedProduct.base_price) + parseFloat(variant.additional_price))"></span>
                                                </div>
                                            </label>
                                        </template>
                                    </div>
                                </div>
                            </template>

                            <!-- Special Instructions -->
                            <div class="mt-8 mb-4">
                                <h4 class="text-xs font-bold tracking-widest text-gray-500 uppercase mb-3">Instruksi Khusus</h4>
                                <textarea x-model="specialInstructions" rows="2" class="w-full text-sm border-gray-800 rounded-xl focus:ring-accent focus:border-accent p-4 bg-black/40 text-gray-200 placeholder-gray-600 transition" placeholder="Contoh: Less ice, extra sugar..."></textarea>
                            </div>
                        </div>

                        <!-- Action Area -->
                        <div class="p-6 bg-[#0a0a0c] border-t border-gray-800 flex items-center justify-between shrink-0">
                            <div class="flex items-center bg-black/50 border border-gray-800 rounded-xl h-14 w-32 shadow-inner">
                                <button @click="if(quantity > 1) quantity--" class="flex-1 text-gray-500 hover:text-white transition h-full font-bold text-xl flex items-center justify-center">-</button>
                                <span class="font-medium text-lg w-8 text-center text-white" x-text="quantity"></span>
                                <button @click="quantity++" class="flex-1 text-gray-500 hover:text-white transition h-full font-bold text-xl flex items-center justify-center">+</button>
                            </div>
                            
                            <button @click="placeOrder()" class="ml-4 flex-1 bg-accent hover:bg-[#d6bc8b] text-gray-900 h-14 px-5 rounded-xl font-bold text-sm sm:text-base transition-all shadow-[0_0_20px_rgba(200,169,126,0.2)] hover:shadow-[0_0_25px_rgba(200,169,126,0.4)] flex justify-between items-center whitespace-nowrap transform hover:-translate-y-0.5">
                                <span>Add to Order</span>
                                <span class="bg-black/10 px-2 py-1 rounded text-sm tracking-wide" x-text="formatMoney(calculateTotal())"></span>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Cart FAB Button -->
    <div x-show="cartCount > 0" style="display:none"
         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-75" x-transition:enter-end="opacity-100 scale-100"
         class="fixed bottom-6 right-6 z-40">
        <button @click="cartOpen = true"
                class="relative bg-accent hover:bg-[#d6bc8b] text-gray-900 font-bold px-6 py-4 rounded-2xl shadow-2xl shadow-accent/30 flex items-center space-x-3 transition-all hover:-translate-y-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <span x-text="formatMoney(cartTotal)"></span>
            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold w-6 h-6 rounded-full flex items-center justify-center" x-text="cartCount"></span>
        </button>
    </div>

    <!-- Cart Drawer -->
    <div x-show="cartOpen" style="display:none" class="fixed inset-0 z-50 flex justify-end">
        <div @click="cartOpen = false" class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
        <div x-show="cartOpen"
             x-transition:enter="ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
             class="relative w-full max-w-sm bg-[#121212] border-l border-gray-800 h-full flex flex-col overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-800 flex items-center justify-between">
                <h3 class="font-heading text-lg font-bold text-white">Keranjang (<span x-text="cartCount"></span>)</h3>
                <button @click="cartOpen = false" class="text-gray-500 hover:text-white"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <div class="flex-1 overflow-y-auto p-5 space-y-3">
                <template x-for="(item, idx) in cart" :key="idx">
                    <div class="flex items-start justify-between bg-white/5 rounded-xl p-3 border border-white/10">
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-white" x-text="item.name"></p>
                            <p x-show="item.variant" class="text-xs text-gray-500" x-text="item.variant"></p>
                            <p class="text-xs text-accent mt-1" x-text="`${item.qty}x @ ${formatMoney(item.price)}`"></p>
                        </div>
                        <div class="text-right ml-3">
                            <p class="text-sm font-bold text-white" x-text="formatMoney(item.subtotal)"></p>
                            <button @click="removeFromCart(idx)" class="text-red-500 text-xs hover:text-red-400 mt-1">Hapus</button>
                        </div>
                    </div>
                </template>
            </div>
            <div class="p-5 border-t border-gray-800 bg-[#0a0a0c]">
                <div class="flex justify-between text-lg font-bold text-white mb-4">
                    <span>Total</span><span class="text-accent" x-text="formatMoney(cartTotal)"></span>
                </div>
                <button @click="openCheckout()"
                        class="w-full bg-accent hover:bg-[#d6bc8b] text-gray-900 font-bold py-3.5 rounded-xl transition shadow-lg">
                    Bayar via QRIS →
                </button>
            </div>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div x-show="checkoutOpen" style="display:none" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div @click="!submitting && (checkoutOpen=false)" class="absolute inset-0 bg-black/80 backdrop-blur-md"></div>
        <div class="relative w-full max-w-md bg-[#121212] border border-gray-800 rounded-3xl overflow-hidden shadow-2xl">
            <!-- Step 1: Customer Form -->
            <div x-show="checkoutStep === 1">
                <div class="px-6 py-5 border-b border-gray-800">
                    <h3 class="font-heading text-xl font-bold text-white">Detail Pesanan</h3>
                    <p class="text-xs text-gray-500 mt-1">Isi data Anda sebelum lanjut ke pembayaran QRIS</p>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="text-xs text-gray-400 mb-1 block">Nama Anda *</label>
                        <input x-model="customerName" type="text" placeholder="Contoh: Budi"
                               class="w-full bg-black/50 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:border-accent focus:ring-0 placeholder-gray-600">
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 mb-1 block">Nomor HP (opsional)</label>
                        <input x-model="customerPhone" type="tel" placeholder="08xxxxxxxxxx"
                               class="w-full bg-black/50 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:border-accent focus:ring-0 placeholder-gray-600">
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 mb-1 block">Nomor Meja / Take Away</label>
                        <input x-model="customerTable" type="text" placeholder="Meja 3 / Take Away"
                               class="w-full bg-black/50 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:border-accent focus:ring-0 placeholder-gray-600">
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 mb-1 block">Catatan Tambahan</label>
                        <textarea x-model="orderNotes" rows="2" placeholder="Alergi, dll..."
                                  class="w-full bg-black/50 border border-gray-700 rounded-xl px-4 py-3 text-white text-sm focus:border-accent focus:ring-0 placeholder-gray-600"></textarea>
                    </div>
                    <!-- Summary -->
                    <div class="bg-white/5 rounded-xl p-4 border border-white/10">
                        <template x-for="item in cart" :key="item.product_id">
                            <div class="flex justify-between text-xs text-gray-400 mb-1">
                                <span x-text="`${item.qty}x ${item.name}`"></span>
                                <span x-text="formatMoney(item.subtotal)"></span>
                            </div>
                        </template>
                        <div class="flex justify-between font-bold text-white pt-2 border-t border-white/10 mt-2">
                            <span>Total</span><span class="text-accent" x-text="formatMoney(cartTotal)"></span>
                        </div>
                    </div>
                </div>
                <div class="px-6 pb-6 flex space-x-3">
                    <button @click="checkoutOpen=false" class="flex-1 border border-gray-700 text-gray-400 hover:text-white py-3 rounded-xl text-sm transition">Batal</button>
                    <button @click="submitCheckout()" :disabled="submitting"
                            class="flex-1 bg-accent hover:bg-[#d6bc8b] disabled:opacity-50 text-gray-900 font-bold py-3 rounded-xl text-sm transition">
                        <span x-show="!submitting">Lanjut ke QRIS →</span>
                        <span x-show="submitting">Memproses...</span>
                    </button>
                </div>
            </div>

            <!-- Step 2: QRIS + Upload Proof -->
            <div x-show="checkoutStep === 2">
                <div class="px-6 py-5 border-b border-gray-800">
                    <p class="text-xs text-accent font-bold uppercase tracking-widest">Kode Order: <span x-text="orderCode"></span></p>
                    <h3 class="font-heading text-xl font-bold text-white mt-1">Scan & Bayar QRIS</h3>
                </div>
                <div class="p-6">
                    <div class="flex justify-center mb-4">
                        <div class="bg-white p-3 rounded-2xl shadow-lg">
                            <img src="{{ asset('images/qris.jpg') }}" alt="QRIS" class="w-52 h-52 object-contain rounded-xl">
                        </div>
                    </div>
                    <p class="text-center text-accent font-bold text-xl mb-1" x-text="formatMoney(snapshotTotal)"></p>
                    <p class="text-center text-xs text-gray-500 mb-5">Scan dengan GoPay, OVO, DANA, m-Banking, dll.</p>

                    <!-- Upload Proof -->
                    <div x-show="!proofDone">
                        <label class="block text-xs text-gray-400 mb-2">Upload Bukti Pembayaran</label>
                        <label class="block border-2 border-dashed border-gray-700 hover:border-accent rounded-xl p-4 text-center cursor-pointer transition group" for="proof-file">
                            <input type="file" id="proof-file" accept="image/*" class="hidden" @change="proofFile = $event.target.files[0]">
                            <svg class="w-8 h-8 text-gray-600 mx-auto mb-2 group-hover:text-accent transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-xs text-gray-500" x-text="proofFile ? proofFile.name : 'Klik untuk pilih foto bukti'"></p>
                        </label>
                        <button @click="uploadProof()" :disabled="proofUploading || !proofFile"
                                class="w-full mt-3 bg-accent hover:bg-[#d6bc8b] disabled:opacity-40 text-gray-900 font-bold py-3 rounded-xl text-sm transition">
                            <span x-show="!proofUploading">📤 Kirim Bukti Bayar</span>
                            <span x-show="proofUploading">Mengupload...</span>
                        </button>
                    </div>
                    <div x-show="proofDone" class="text-center py-2">
                        <p class="text-green-400 font-bold">✅ Bukti berhasil dikirim!</p>
                        <p class="text-gray-400 text-xs mt-1">Pesanan sedang diproses oleh tim kami.</p>
                        <button @click="goToStatus()" class="mt-3 w-full border border-accent text-accent hover:bg-accent hover:text-gray-900 font-medium py-2.5 rounded-xl text-sm transition">
                            Cek Status Pesanan →
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Toast -->
    <div x-show="showNotification" style="display: none;"
         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-10" x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-10"
         class="fixed bottom-24 left-1/2 transform -translate-x-1/2 glass-card border-l-4 border-l-green-500 text-white px-5 py-3 rounded-xl shadow-2xl z-40 flex items-center whitespace-nowrap">
        <div class="bg-green-500/20 p-1.5 rounded-full mr-3"><svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div>
        <p class="text-sm font-medium" x-text="notifMsg"></p>
    </div>

    <!-- Footer -->
    <footer class="mt-20 text-center text-gray-600 border-t border-white/5 pt-10">
        <h2 class="text-2xl font-heading text-white opacity-50 mb-2">GARAGE</h2>
        <p class="text-xs tracking-wider uppercase">&copy; {{ date('Y') }} Garage Coffee.</p>
        <div class="mt-3 pb-8 flex items-center justify-center space-x-4">
            <a href="{{ route('qris.page') }}" class="text-xs text-gray-600 hover:text-accent transition">Halaman QRIS</a>
            <span class="text-gray-700">·</span>
            <a href="{{ route('login') }}" class="text-xs text-gray-700 hover:text-accent transition">Staff Login</a>
        </div>
    </footer>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        function menuApp() {
            const productsData = @json($products);
            const products = productsData.map(p => ({
                ...p,
                image_url: p.image ? '{{ Storage::url('') }}' + p.image : null
            }));

            return {
                products,
                activeCategory: 'all',
                isModalOpen: false,
                selectedProduct: null,
                selectedVariant: null,
                quantity: 1,
                specialInstructions: '',
                showNotification: false,
                notifMsg: 'Ditambahkan ke keranjang!',

                // Cart
                cart: [],
                cartOpen: false,
                checkoutOpen: false,
                checkoutStep: 1, // 1=form, 2=qris+upload
                orderCode: null,
                orderId: null,
                snapshotTotal: 0,
                submitting: false,
                proofFile: null,
                proofUploading: false,
                proofDone: false,
                customerName: '',
                customerPhone: '',
                customerTable: '',
                orderNotes: '',

                get filteredProducts() {
                    const f = this.activeCategory === 'all'
                        ? this.products
                        : this.products.filter(p => p.category_id === this.activeCategory);
                    return f.sort((a,b) => (b.is_featured?1:0)-(a.is_featured?1:0));
                },

                get cartTotal() {
                    return this.cart.reduce((s,i) => s + i.subtotal, 0);
                },

                get cartCount() {
                    return this.cart.reduce((s,i) => s + i.qty, 0);
                },

                openModal(product) {
                    if(!product.is_available) return;
                    this.selectedProduct = product;
                    this.selectedVariant = null;
                    this.quantity = 1;
                    this.specialInstructions = '';
                    this.isModalOpen = true;
                    document.body.classList.add('overflow-hidden');
                },

                closeModal() {
                    this.isModalOpen = false;
                    setTimeout(() => { this.selectedProduct = null; }, 300);
                    document.body.classList.remove('overflow-hidden');
                },

                calculateTotal() {
                    if (!this.selectedProduct) return 0;
                    let price = parseFloat(this.selectedProduct.base_price);
                    if (this.selectedVariant) {
                        const v = this.selectedProduct.variants.find(v => v.id === this.selectedVariant);
                        if(v) price += parseFloat(v.additional_price);
                    }
                    return price * this.quantity;
                },

                placeOrder() {
                    const variantObj = this.selectedVariant
                        ? this.selectedProduct.variants.find(v => v.id === this.selectedVariant)
                        : null;
                    const unitPrice = this.calculateTotal() / this.quantity;
                    this.cart.push({
                        product_id: this.selectedProduct.id,
                        name: this.selectedProduct.name,
                        variant: variantObj ? variantObj.name : null,
                        qty: this.quantity,
                        price: unitPrice,
                        subtotal: this.calculateTotal(),
                        notes: this.specialInstructions,
                    });
                    this.closeModal();
                    this.notifMsg = `${this.selectedProduct ? this.selectedProduct.name : 'Item'} ditambahkan!`;
                    this.showNotification = true;
                    setTimeout(() => this.showNotification = false, 3000);
                },

                removeFromCart(idx) {
                    this.cart.splice(idx, 1);
                },

                openCheckout() {
                    this.cartOpen = false;
                    this.checkoutStep = 1;
                    this.checkoutOpen = true;
                },

                async submitCheckout() {
                    if (!this.customerName.trim()) { alert('Nama harus diisi!'); return; }
                    this.submitting = true;
                    this.snapshotTotal = this.cartTotal;
                    const fd = new FormData();
                    fd.append('customer_name', this.customerName);
                    fd.append('customer_phone', this.customerPhone);
                    fd.append('customer_table', this.customerTable);
                    fd.append('items', JSON.stringify(this.cart));
                    fd.append('total_amount', this.snapshotTotal);
                    fd.append('notes', this.orderNotes);
                    fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                    try {
                        const res = await fetch('{{ route("order.submit") }}', { method: 'POST', body: fd });
                        const data = await res.json();
                        if (data.success) {
                            this.orderCode = data.order_code;
                            this.orderId = data.order_id;
                            this.checkoutStep = 2;
                        } else { alert('Gagal membuat order. Coba lagi.'); }
                    } catch(e) { alert('Error: ' + e.message); }
                    this.submitting = false;
                },

                async uploadProof() {
                    if (!this.proofFile) { alert('Pilih foto bukti bayar dulu!'); return; }
                    this.proofUploading = true;
                    const fd = new FormData();
                    fd.append('payment_proof', this.proofFile);
                    fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                    try {
                        const res = await fetch(`/order/${this.orderId}/proof`, { method: 'POST', body: fd });
                        const data = await res.json();
                        if (data.success) {
                            this.proofDone = true;
                            this.cart = [];
                        }
                    } catch(e) { alert('Gagal upload. Coba lagi.'); }
                    this.proofUploading = false;
                },

                goToStatus() {
                    window.location.href = `/order/${this.orderCode}/status`;
                },

                formatMoney(amount) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(amount);
                }
            }
        }
    </script>
</body>
</html>
