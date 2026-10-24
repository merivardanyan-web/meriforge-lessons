<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// «What I added»․ ակումբի անդամների գնահատականները — PHP Դաս 22, տնային 2
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('stars');                 // 1–5
            $table->timestamps();

            $table->unique(['user_id', 'book_id']);               // ⭐ մեկ անդամ՝ մեկ գնահատական գրքին
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
