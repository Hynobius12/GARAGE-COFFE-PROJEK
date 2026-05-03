<?php
use App\Http\Controllers\Barista\KdsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('menu.index');
});

// ─── Public E-Menu & QRIS ──────────────────────────────────────────────────
Route::get('/menu',  [\App\Http\Controllers\Web\MenuController::class, 'index'])->name('menu.index');
Route::get('/qris',  [\App\Http\Controllers\Web\MenuController::class, 'qrisPage'])->name('qris.page');

Route::post('/order/submit',           [\App\Http\Controllers\Web\MenuController::class, 'submitOrder'])->name('order.submit');
Route::post('/order/{qrisOrder}/proof', [\App\Http\Controllers\Web\MenuController::class, 'uploadProof'])->name('order.proof');
Route::get('/order/{orderCode}/status', [\App\Http\Controllers\Web\MenuController::class, 'orderStatus'])->name('order.status');

// ─── Auth ──────────────────────────────────────────────────────────────────
Route::get('/dashboard', function () {
    $role = auth()->user()->role;
    if ($role === 'owner') return redirect()->route('admin.dashboard');
    if ($role === 'cashier') return redirect()->route('cashier.pos');
    if ($role === 'barista') return redirect()->route('barista.kds');
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// ─── Admin ────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:owner'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Produk & Kategori
    // Receipt for web orders (QRIS)
    Route::middleware(['auth', 'role:owner|cashier|admin'])
        ->get('/order/{order}/receipt', [\App\Http\Controllers\Admin\QrisOrderController::class, 'receipt'])
        ->name('order.receipt');
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('products',   \App\Http\Controllers\Admin\ProductController::class);

    // Resep & Varian Produk
    Route::get('/products/{product}/recipes',              [\App\Http\Controllers\Admin\ProductRecipeController::class, 'index'])->name('products.recipes.index');
    Route::post('/products/{product}/recipes',             [\App\Http\Controllers\Admin\ProductRecipeController::class, 'store'])->name('products.recipes.store');
    Route::delete('/products/{product}/recipes/{recipe}',  [\App\Http\Controllers\Admin\ProductRecipeController::class, 'destroy'])->name('products.recipes.destroy');

    Route::get('/products/{product}/variants',             [\App\Http\Controllers\Admin\ProductVariantController::class, 'index'])->name('products.variants.index');
    Route::post('/products/{product}/variants',            [\App\Http\Controllers\Admin\ProductVariantController::class, 'store'])->name('products.variants.store');
    Route::delete('/products/{product}/variants/{variant}',[\App\Http\Controllers\Admin\ProductVariantController::class, 'destroy'])->name('products.variants.destroy');

    // Orders (dari kasir)
    Route::get('orders',           [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}',   [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');

    // Inventori (Raw Materials)
    Route::resource('raw-materials', \App\Http\Controllers\Admin\RawMaterialController::class)->parameters([
        'raw-materials' => 'rawMaterial'
    ]);

    // Laporan
    Route::get('/reports',               [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export-excel',  [\App\Http\Controllers\Admin\ReportController::class, 'exportExcel'])->name('reports.excel');
    Route::get('/reports/export-pdf',    [\App\Http\Controllers\Admin\ReportController::class, 'exportPdf'])->name('reports.pdf');
});

// ─── Shared QRIS Management (Owner, Cashier, Barista) ───────────────────
Route::middleware(['auth', 'role:owner|cashier|barista'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('qris-orders',                           [\App\Http\Controllers\Admin\QrisOrderController::class, 'index'])->name('qris-orders.index');
    Route::get('qris-orders/{qrisOrder}',               [\App\Http\Controllers\Admin\QrisOrderController::class, 'show'])->name('qris-orders.show');
    Route::post('qris-orders/{qrisOrder}/confirm',      [\App\Http\Controllers\Admin\QrisOrderController::class, 'confirm'])->name('qris-orders.confirm');
    Route::post('qris-orders/{qrisOrder}/process',      [\App\Http\Controllers\Admin\QrisOrderController::class, 'process'])->name('qris-orders.process');
    Route::post('qris-orders/{qrisOrder}/complete',     [\App\Http\Controllers\Admin\QrisOrderController::class, 'complete'])->name('qris-orders.complete');
    Route::post('qris-orders/{qrisOrder}/reject',       [\App\Http\Controllers\Admin\QrisOrderController::class, 'reject'])->name('qris-orders.reject');
});

Route::middleware(['auth', 'role:barista'])->group(function () {
    // Menampilkan halaman KDS
    // Route::get('/barista/kds', function () {
    //     $orders = \App\Models\Order::whereIn('status', ['pending', 'processing'])->get();
    //     return view('barista.kds', compact('orders'));
    // })->name('barista.kds');

    Route::get('/barista/kds', [KdsController::class, 'index'])->name('barista.kds');

    // Menangani aksi PROSES PESANAN
    Route::post('/barista/kds/{id}/process', function ($id) {
        \App\Models\Order::where('id', $id)->update(['status' => 'processing']);
        return back();
    })->name('barista.kds.process');

    // Menangani aksi TANDAI SELESAI
    Route::post('/barista/kds/{id}/complete', function ($id) {
        \App\Models\Order::where('id', $id)->update(['status' => 'completed']);
        return back();
    })->name('barista.kds.complete');
});

// ─── Cashier ──────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:owner|cashier'])->prefix('cashier')->name('cashier.')->group(function () {
    Route::get('/pos',              [\App\Http\Controllers\Cashier\POSController::class, 'index'])->name('pos');
    Route::post('/pos/checkout',    [\App\Http\Controllers\Cashier\POSController::class, 'checkout'])->name('pos.checkout');
    Route::get('/pos/history',      [\App\Http\Controllers\Cashier\POSController::class, 'history'])->name('pos.history');
    Route::post('/pos/orders/{type}/{id}/status', [\App\Http\Controllers\Cashier\POSController::class, 'updateStatus'])->name('pos.updateStatus');
});
