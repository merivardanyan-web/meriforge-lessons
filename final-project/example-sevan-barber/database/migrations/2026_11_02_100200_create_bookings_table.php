<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            // restrict․ ամրագրումների պատմությունը չի կորչում հաճախորդին կամ ժամը ջնջելիս
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('time_slot_id')->constrained()->restrictOnDelete();
            $table->foreignId('service_id')->constrained()->restrictOnDelete();

            // ⭐ «Դժվար ֆունկցիան»․ ակտիվ ամրագրման դեպքում = time_slot_id, չեղարկվածի դեպքում՝ NULL։
            // UNIQUE-ը թույլ է տալիս շատ NULL, ուստի չեղարկված ժամը կարելի է ամրագրել նորից,
            // իսկ երկու ակտիվ ամրագրում նույն ժամին բազան չի ընդունի՝ նույնիսկ միաժամանակյա հարցումներով
            $table->unsignedBigInteger('active_slot_id')->nullable()->unique();

            $table->unsignedInteger('price');                    // գինը ամրագրման պահին — PHP Դաս 9
            $table->string('status', 10)->default('booked');     // booked · done · cancelled
            $table->string('note', 255)->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
