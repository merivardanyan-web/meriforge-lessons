<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// php artisan make:migration add_series_to_books_table — PHP Դաս 20, տնային 3
// php artisan migrate, ապա php artisan migrate:rollback․ down()-ը հանում է սյունակը
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->string('series', 100)->nullable()->after('author');     // «Հարրի Փոթեր», «Մատանիների տիրակալը»
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('series');
        });
    }
};
