<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Service;
use App\Models\TimeSlot;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * php artisan migrate:fresh --seed
 * Ժամերը հաշվվում են «այսօրից»․ demo-ն միշտ ունի առաջիկա ազատ ժամեր և անցած այցեր
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        mt_srand(7);                                   // նույն տվյալները ամեն անգամ

        // ---------- Մարդիկ ----------
        $admin = $this->user('Գոռ Սևանյան', 'admin@sevanbarber.test', 'admin', env('ADMIN_PASSWORD', 'Admin-12345'), '+374 91 100100');
        $aram = $this->user('Արամ Հակոբյան', 'aram@sevanbarber.test', 'staff', 'Staff-12345', '+374 93 200200');
        $levon = $this->user('Լևոն Գրիգորյան', 'levon@sevanbarber.test', 'staff', 'Staff-12345', '+374 94 300300');
        $demo = $this->user('Demo հաճախորդ', 'demo@sevanbarber.test', 'client', env('DEMO_PASSWORD', 'Demo-12345'), '+374 55 000000');
        $mariam = $this->user('Մարիամ Պողոսյան', 'mariam@example.com', 'client', 'Client-12345', '+374 77 123456');
        // Factory-ն (Faker) require-dev-ում է և hosting-ում (composer install --no-dev) չկա,
        // ուստի demo-ի հաճախորդները՝ ձեռքով։ UserFactory-ն մնում է tinker-ի և թեստերի համար
        $clients = collect([$demo, $mariam]);
        foreach (['Դավիթ Մարտիրոսյան', 'Տիգրան Սարգսյան', 'Նարեկ Ավետիսյան', 'Հայկ Վարդանյան',
                  'Սամվել Խաչատրյան', 'Էրիկ Հովհաննիսյան', 'Վահե Մկրտչյան', 'Արթուր Ղազարյան'] as $i => $name) {
            $clients->push($this->user($name, "client{$i}@example.com", 'client', 'Client-12345', '+374 9' . ($i % 9 + 1) . ' ' . (500100 + $i * 7311)));
        }

        // ---------- Ծառայություններ ----------
        $services = collect([
            ['Տղամարդու սանրվածք', 45, 5000, 'Լվացում, սանրվածք մեքենայով և մկրատով, հարդարում։'],
            ['Մորուքի ձևավորում', 30, 3000, 'Եզրագծեր, տաք սրբիչ, յուղ։'],
            ['Սանրվածք + մորուք', 60, 7500, 'Երկուսը միասին՝ 500 դրամ զեղչով։'],
            ['Մանկական սանրվածք', 30, 3500, 'Մինչև 12 տարեկան։ Համբերությամբ։'],
            ['Դասական սափրում ածելիով', 40, 4500, 'Տաք սրբիչ, փրփուր, ածելի, թթվեցնող լոսյոն։'],
            ['Գլխի լվացում և մերսում', 20, 2000, 'Կարճ հանգիստ աշխատանքային օրվա մեջ։'],
        ])->map(function ($s) {
            $service = new Service(['name' => $s[0], 'duration_min' => $s[1], 'price' => $s[2], 'description' => $s[3], 'is_active' => true]);
            $service->slug = Service::uniqueSlugFor($s[0]);
            $service->save();

            return $service;
        });

        // Ջնջված ծառայություն՝ ջնջվածների էջի և պատմության համար
        $old = new Service(['name' => 'Մազերի ներկում', 'duration_min' => 60, 'price' => 9000, 'is_active' => true]);
        $old->slug = 'mazeri-nerkum';
        $old->save();

        // ---------- Ժամեր․ 3 օր առաջ → 7 օր հետո, 10:00–18:00, կիրակի՝ հանգիստ ----------
        foreach ([$aram, $levon] as $barber) {
            for ($d = -3; $d <= 7; $d++) {
                $day = today()->addDays($d);
                if ($day->isSunday()) {
                    continue;
                }
                $rows = [];
                for ($h = 10; $h < 19; $h++) {
                    $rows[] = ['staff_id' => $barber->id, 'starts_at' => $day->copy()->setTime($h, 0), 'created_at' => now(), 'updated_at' => now()];
                }
                TimeSlot::insert($rows);
            }
        }

        // ---------- Ամրագրումներ ----------
        // Անցած ժամերի մոտ կեսը՝ կատարված, մի քանիսը՝ չեղարկված
        TimeSlot::where('starts_at', '<', now())->get()->each(function (TimeSlot $slot) use ($clients, $services, $old) {
            $r = mt_rand(1, 100);
            if ($r > 55) {
                return;
            }
            $service = $r <= 4 ? $old : $services->random();
            $this->booking($clients->random(), $slot, $service, $r <= 10 ? 'cancelled' : 'done');
        });
        $old->delete();

        // Առաջիկա ժամերի մոտ 30%-ը՝ զբաղված (բայց ոչ demo-ի համար)
        $future = TimeSlot::where('starts_at', '>', now()->addHour())->orderBy('starts_at')->get();
        $future->each(function (TimeSlot $slot) use ($clients, $services, $demo) {
            if (mt_rand(1, 100) <= 30) {
                $this->booking($clients->where('id', '!=', $demo->id)->random(), $slot, $services->random(), 'booked');
            }
        });

        // Demo-ն՝ մեկ առաջիկա ամրագրում (վաղը կամ հաջորդ աշխատանքային օրը) և մեկ անցած
        $tomorrow = TimeSlot::free()->where('staff_id', $aram->id)->where('starts_at', '>', today()->addDay()->setTime(14, 0))->orderBy('starts_at')->first();
        $this->booking($demo, $tomorrow, $services[2], 'booked', 'Կարճ կողքերից, խնդրում եմ');
        $past = TimeSlot::free()->where('staff_id', $levon->id)->where('starts_at', '<', now()->subDay())->orderByDesc('starts_at')->first();
        $this->booking($demo, $past, $services[0], 'done');
    }

    private function user(string $name, string $email, string $role, string $password, string $phone): User
    {
        $user = new User(['name' => $name, 'email' => $email, 'password' => $password, 'phone' => $phone]);
        $user->role = $role;                             // role-ը $fillable-ում չէ ⭐
        $user->email_verified_at = now();
        $user->save();

        return $user;
    }

    private function booking(User $client, TimeSlot $slot, Service $service, string $status, ?string $note = null): void
    {
        $b = new Booking();
        $b->user_id = $client->id;
        $b->time_slot_id = $slot->id;
        $b->active_slot_id = $status === 'cancelled' ? null : $slot->id;
        $b->service_id = $service->id;
        $b->price = $service->price;
        $b->status = $status;
        $b->note = $note;
        $b->cancelled_at = $status === 'cancelled' ? $slot->starts_at->copy()->subDay() : null;
        $b->created_at = $slot->starts_at->copy()->subDays(mt_rand(1, 5));
        $b->save();
    }
}
