<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Demo հաշիվներ hosting-ի համար — PHP Դաս 22
 * php artisan db:seed --class=DemoSeeder --force
 * Գաղտնաբառերը՝ .env-ից, ոչ կոդից։ Demo-ն editor է․ ավելացնում և խմբագրում է, չի ջնջում
 */
class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $admin = env('ADMIN_PASSWORD');
        $demo  = env('DEMO_PASSWORD');

        if (! $admin || ! $demo || strlen($admin) < 12) {
            $this->command->error('Լրացրու ADMIN_PASSWORD (12+ նիշ) և DEMO_PASSWORD .env-ում');
            return;
        }

        $user = User::firstOrNew(['email' => env('ADMIN_EMAIL', 'ani@example.com')]);
        $user->name = 'Անի';
        $user->password = $admin;
        $user->role = 'admin';
        $user->save();

        $user = User::firstOrNew(['email' => 'demo@ani-club.example.am']);
        $user->name = 'Demo';
        $user->password = $demo;
        $user->role = 'editor';                // ոչ admin ⭐
        $user->save();

        $this->command->info('Պատրաստ է՝ admin և demo (editor)');
    }
}
