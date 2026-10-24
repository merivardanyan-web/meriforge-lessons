<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Առաջադրանք 2-ի products աղյուսակը՝ PHP-ով, ոչ SQL-ով — PHP Դաս 20
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // FOREIGN KEY ... ON DELETE SET NULL — Դաս 9
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();

            $table->string('name', 200);
            $table->string('slug', 200)->unique();
            $table->string('specs')->nullable();
            $table->text('description')->nullable();

            $table->unsignedInteger('price');                  // դրամով, առանց DECIMAL-ի թակարդի ⭐
            $table->unsignedTinyInteger('discount')->default(0);
            $table->unsignedInteger('stock')->default(0);

            $table->enum('type', ['physical', 'digital'])->default('physical');   // Դաս 13
            $table->unsignedInteger('weight')->default(0);

            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();                             // deleted_at — Դաս 20

            $table->index(['is_active', 'price']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
