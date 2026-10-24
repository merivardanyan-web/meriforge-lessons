<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Demo հաշիվ hosting-ի համար — PHP Դաս 22
 * php artisan db:seed --class=DemoSeeder --force
 * Մենեջեր․ կարող է ավելացնել և խմբագրել, չի կարող ջնջել (delete-products Gate)
 */
class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $password = env('DEMO_PASSWORD');

        if (! $password) {
            $this->command->error('Լրացրու DEMO_PASSWORD-ը .env-ում');
            return;
        }

        $user = User::firstOrNew(['email' => 'demo@technomarket.example.am']);
        $user->name = 'Demo';
        $user->password = $password;
        $user->role = 'manager';               // ոչ admin ⭐
        $user->save();

        $this->command->info('Demo հաշիվը պատրաստ է՝ manager դերով');
    }
}
