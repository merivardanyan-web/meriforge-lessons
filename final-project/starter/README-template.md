# <Նախագծի անունը>

<!-- 2 նախադասություն․ ինչ է անում և ում համար։ Երրորդը՝ «դժվար ֆունկցիան» մեկ տողով -->

**Demo՝** https://… · մուտք՝ `demo@…` (գաղտնաբառը՝ պահոցի About-ում, ոչ այստեղ)

![<ինչ է երևում>](docs/demo.gif)

## Stack

PHP 8.3 · Laravel 12 · MySQL · Blade · Fortify · vanilla JS (fetch) · սեփական CSS

## Features

-
-

## The hard part ⭐

<!-- Ինչ խնդիր։ Ինչու միամիտ լուծումը չի աշխատում։ Ինչ արեցիր (կոդի կտոր)։ Ինչպես ստուգեցիր։
     Սա README-ի ամենակարևոր բաժինն է․ ռեկրուտերը կարդում է հենց այն -->

## Security decisions

<!-- Միայն այն, ինչ իրոք կա կոդում։ README-ում պնդում, որ կոդում չկա → միավորի կորուստ -->
- `role`-ը `$fillable`-ում չէ …
- …-ը ստուգվում է կոնտրոլերում (`Gate::authorize` / Policy), ոչ միայն `@can`-ով
-

## How I used AI

<!-- Ինչի համար՝ կոնկրետ։ Եվ ինչի համար ՉԵՍ օգտագործել (դժվար ֆունկցիան) -->
-
- Did NOT use AI for …

## Run locally

```bash
git clone https://github.com/<you>/<repo>.git
cd <repo>
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve
```

## What I would do next

-
