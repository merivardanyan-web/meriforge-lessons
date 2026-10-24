<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/** Առաջադրանք 1–2-ի seed.sql-ը՝ PHP-ով — PHP Դաս 20 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [];

        foreach ([
            'laptops' => 'Նոութբուքեր',
            'monitors' => 'Մոնիտորներ',
            'phones' => 'Սմարթֆոններ',
            'tablets' => 'Պլանշետներ',
            'accessories' => 'Աքսեսուարներ',
        ] as $slug => $name) {
            $categories[$slug] = Category::create(['name' => $name, 'slug' => $slug]);
        }

        // [կատեգորիա, անուն, համառոտ, գին, զեղչ, պահեստ, քաշ, նկար, ավելացվել է, նկարագրություն]
        $products = [
            ['laptops', 'Նոութբուք Pro 14', '16 ԳԲ / 512 ԳԲ SSD', 450000, 30, 12, 1400, 'laptop.jpg', '2026-03-02',
                '14 դյույմանոց թեթև նոութբուք աշխատանքի և ուսման համար։ 16 ԳԲ հիշողությունը բավական է միաժամանակ բրաուզերի, կոդի խմբագրիչի և զանգերի համար, իսկ մարտկոցը դիմանում է ամբողջ աշխատանքային օրվան։'],
            ['monitors', 'Մոնիտոր 27 4K', 'IPS · 60 Հց · USB-C', 185000, 0, 6, 6200, 'monitor.jpg', '2026-09-20',
                '27 դյույմանոց 4K մոնիտոր՝ հստակ տեքստով և ճշգրիտ գույներով։ Մեկ USB-C մալուխով միանում է նոութբուքին և միաժամանակ լիցքավորում այն։'],
            ['accessories', 'Ստեղնաշար մեխանիկական', 'RGB · հայերեն տառեր', 42000, 0, 0, 900, 'keyboard.jpg', '2026-05-14',
                'Մեխանիկական ստեղնաշար՝ հայերեն և լատինական տառերով։ Ամեն ստեղն ունի իր լուսավորությունը, իսկ մետաղե իրանը չի ճկվում։'],
            ['phones', 'Սմարթֆոն X 128', '6.1" · 128 ԳԲ', 310000, 0, 9, 180, 'smartphone.jpg', '2026-06-30',
                '6.1 դյույմանոց OLED էկրանով սմարթֆոն և երկու տեսախցիկ։ Մարտկոցը բավական է մեկ ամբողջ օրվա համար։'],
            ['accessories', 'Ականջակալ Studio', 'Bluetooth · աղմուկի նվազեցում', 95000, 15, 14, 250, 'headphones.jpg', '2026-04-11',
                'Անլար ականջակալներ՝ ակտիվ աղմուկի նվազեցմամբ։ Մինչև 30 ժամ երաժշտություն մեկ լիցքավորումով։'],
            ['tablets', 'Պլանշետ 11', '11" · 128 ԳԲ · Wi-Fi', 240000, 0, 7, 480, 'tablet.jpg', '2026-02-08',
                '11 դյույմանոց պլանշետ կարդալու, նկարելու և ֆիլմեր դիտելու համար։ Աջակցում է ստիլուս։'],
            ['accessories', 'Մկնիկ անլար', 'Bluetooth · 3 կոճակ', 18000, 0, 35, 90, 'mouse.jpg', '2026-10-01',
                'Լուռ սեղմումներով անլար մկնիկ։ Մեկ մարտկոցը բավական է մինչև 12 ամիս։'],
            ['accessories', 'Բարձրախոս Mini', 'Bluetooth · 10 ժամ', 42000, 17, 3, 400, 'speaker.jpg', '2026-07-19',
                'Փոքր, բայց բարձր բարձրախոս՝ ջրից պաշտպանված իրանով։ Հարմար է արշավների և խոհանոցի համար։'],
            ['accessories', 'Ժամացույց Fit', 'AMOLED · GPS', 65000, 0, 0, 60, 'smartwatch.jpg', '2026-08-25',
                'Խելացի ժամացույց՝ GPS-ով, քայլերի և քնի հաշվիչով։ Լիցքը բավական է մեկ շաբաթ։'],
            ['laptops', 'Նոութբուք Air 13', '8 ԳԲ / 256 ԳԲ SSD', 289000, 0, 5, 1100, 'laptop-2.jpg', '2026-09-05',
                'Ամենաթեթև նոութբուքը մեր խանութում՝ ընդամենը 1.1 կգ։ Լուռ է, որովհետև օդափոխիչ չունի։'],
            ['laptops', 'Նոութբուք Studio 16', '32 ԳԲ / 1 ՏԲ SSD', 580000, 10, 2, 2100, 'laptop-3.jpg', '2026-10-09',
                '16 դյույմանոց նոութբուք՝ տեսանյութերի մոնտաժի, դիզայնի և ծանր նախագծերի համար։'],
            ['laptops', 'Նոութբուք Lite 15', '8 ԳԲ / 512 ԳԲ SSD', 198000, 0, 8, 1800, 'laptop-4.jpg', '2026-01-17',
                'Մատչելի նոութբուք ուսման և տնային գործերի համար՝ 15.6 դյույմանոց էկրանով։'],
        ];

        foreach ($products as [$cat, $name, $specs, $price, $discount, $stock, $weight, $image, $added, $description]) {
            $product = $categories[$cat]->products()->create([
                'name'        => $name,
                'slug'        => Product::uniqueSlugFor($name),
                'specs'       => $specs,
                'description' => $description,
                'price'       => $price,
                'discount'    => $discount,
                'stock'       => $stock,
                'weight'      => $weight,
                'image'       => $image,
            ]);
            // created_at-ը $fillable-ում չէ․ «Նոր» պիտակի համար դնում ենք ուղիղ
            $product->created_at = Carbon::parse($added . ' 10:00');
            $product->save();
        }

        // Թվային ապրանք — Դաս 13
        $categories['accessories']->products()->create([
            'name'        => 'Հակավիրուս Shield · 1 տարի',
            'slug'        => 'hakavirus-shield-1-tari',
            'specs'       => 'Լիցենզիա 3 սարքի համար',
            'description' => 'Թվային լիցենզիա․ բանալին կուղարկենք էլ. փոստով պատվերից անմիջապես հետո։',
            'price'       => 15000,
            'type'        => 'digital',
            'image'       => 'antivirus.jpg',
        ]);

        // ⚠️ Միայն լոկալ մեքենայում
        if (app()->environment('local')) {
            Product::factory()->count(12)->create(['category_id' => $categories['accessories']->id]);

            // role-ը՝ ՈՒՂԻՂ վերագրումով, ոչ create([...])-ով — Դաս 20 ⭐
            foreach ([
                ['Ադմին', 'admin@technomarket.test', 'Admin-12345', 'admin'],
                ['Մենեջեր', 'manager@technomarket.test', 'Manager-12345', 'manager'],
                ['Աննա', 'anna@example.com', 'Anna-12345', 'customer'],
            ] as [$name, $email, $password, $role]) {
                $user = User::create(['name' => $name, 'email' => $email, 'password' => $password]);
                $user->role = $role;
                $user->save();
            }
        }
    }
}
