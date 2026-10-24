<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80);
            $table->string('slug', 100)->unique();
            $table->string('description', 500)->nullable();
            $table->unsignedSmallInteger('duration_min');        // ≤ 60․ մեկ ամրագրում = մեկ ժամ
            $table->unsignedInteger('price');                    // դրամ, ամբողջ թիվ — PHP Դաս 9
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();                               // հին ամրագրումները պետք է իմանան ծառայության անունը
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
