<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Դաս 09-ի books + Դաս 13-ի upgrade-13.sql-ը՝ մեկ միգրացիայում — PHP Դաս 20
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('genre_id')->nullable()->constrained()->nullOnDelete();   // ON DELETE SET NULL
            $table->string('title', 200);
            $table->string('slug', 200)->unique();
            $table->string('author', 100);
            $table->smallInteger('year')->nullable();
            $table->unsignedSmallInteger('pages');
            $table->unsignedSmallInteger('read_pages')->default(0);
            $table->unsignedTinyInteger('rating')->default(0);
            $table->char('color', 7)->default('#003459');
            $table->string('link')->nullable();
            $table->string('cover')->nullable();
            $table->string('format', 10)->default('paper');            // paper · audio · ebook
            $table->unsignedSmallInteger('minutes')->nullable();
            $table->string('narrator', 100)->nullable();
            $table->string('lent_to', 60)->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();                                     // deleted_at — Դաս 20, բաժին 08
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
