<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Վարսավիրի աշխատանքային ժամերը․ մեկ տող = մեկ ժամ
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('time_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('users')->cascadeOnDelete();
            $table->dateTime('starts_at');
            $table->timestamps();

            $table->unique(['staff_id', 'starts_at']);           // ⭐ նույն վարսավիրը՝ նույն ժամին երկու անգամ չի կարող լինել
            $table->index('starts_at');                          // «ազատ ժամերը այսօր» հարցման համար
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('time_slots');
    }
};
