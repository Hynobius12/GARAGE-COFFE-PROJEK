<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qris_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique(); // GC-XXXXXX
            $table->string('customer_name');
            $table->string('customer_phone')->nullable();
            $table->string('customer_table')->nullable(); // nomor meja / take away
            $table->json('items'); // [{product_id, name, variant, qty, price, subtotal}]
            $table->decimal('total_amount', 10, 2);
            $table->text('notes')->nullable();
            $table->enum('status', ['pending_payment', 'payment_uploaded', 'confirmed', 'cancelled'])
                  ->default('pending_payment');
            $table->string('payment_proof')->nullable(); // path file bukti bayar
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qris_orders');
    }
};
