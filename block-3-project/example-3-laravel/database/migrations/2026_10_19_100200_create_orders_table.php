<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();   // հյուրը՝ null

            $table->string('customer_name', 100);
            $table->string('email', 190)->nullable();
            $table->string('phone', 30);
            $table->enum('delivery', ['courier', 'pickup', 'region'])->default('courier');
            $table->string('region', 40)->nullable();
            $table->string('address')->nullable();
            $table->enum('payment', ['cash', 'card'])->default('cash');
            $table->string('comment', 500)->nullable();

            $table->unsignedInteger('shipping')->default(0);
            $table->unsignedInteger('total');
            $table->enum('status', ['new', 'paid', 'shipped', 'cancelled'])->default('new');

            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();       // Դաս 9 ⭐
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('price');                  // գինը ԳՆՄԱՆ ՊԱՀԻՆ
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
