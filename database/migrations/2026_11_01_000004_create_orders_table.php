<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->string('customer_name');
            $table->string('customer_nis')->nullable();
            $table->string('customer_class')->nullable();
            $table->string('customer_room')->nullable();
            $table->string('customer_phone')->nullable();
            $table->unsignedInteger('total_price')->default(0);
            $table->string('payment_method')->default('cash'); // 'cash', 'transfer', 'qris'
            $table->string('status')->default('Pesanan Dibuat'); // 'Pesanan Dibuat', 'Sedang Diproses', 'Siap Diambil', 'Selesai', 'Dibatalkan'
            $table->text('customer_note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

