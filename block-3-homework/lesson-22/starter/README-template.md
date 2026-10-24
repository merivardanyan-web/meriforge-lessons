# Անիի գրքերի ակումբը

Ընթերցանության օրագիր և գրքերի ակումբ․ դարակ ֆիլտրերով, կարդալու ցուցակ, աուդիո և թղթային գրքեր,
ակումբի անդամների գնահատականներ և ադմին վահանակ՝ դերերով։

**Demo՝** https://ani-club.example.am · մուտք՝ `demo@ani-club.example.am` (գաղտնաբառը՝ պահոցի About-ում)

![Դարակ և կարդալու ցուցակ](docs/demo.gif)

## Stack

PHP 8.3 · Laravel 12 · MySQL 8 · Blade · Fortify · vanilla JS (fetch)

## Features

- Դարակ՝ որոնմամբ, ժանրով, կարգավիճակով և դասավորումով, էջավորում
- Կարդալու ցուցակ սեսիայում՝ fetch-ով, առանց JS-ի նույնպես աշխատում է
- Երեք տեսակ՝ թղթային, աուդիո (տևողություն, ընթերցող), էլեկտրոնային
- «Կարդացի այսօր»՝ երկու աղյուսակ մեկ տրանզակցիայով
- Դերեր․ admin (ամեն ինչ), editor (առանց ջնջման), member (գնահատում է)
- JSON API՝ `/api/books`, `/api/books/{slug}`

## What I added

Մեկ ֆունկցիա, որ դասընթացում չկար։ Ի՞նչ է անում, ինչպես է պաշտպանված, ինչ որոշում ընդունեցիր։

## Security decisions

- `role`-ը `$fillable`-ում չէ․ գրանցման ֆորմային ավելացված `role=admin`-ը անտեսվում է
- Ջնջումը պաշտպանված է կոնտրոլերում (`Gate::authorize`), ոչ միայն կոճակը թաքցնելով
- Դասավորումը՝ սպիտակ ցուցակով․ սյունակի անունը օգտագործողից չի գալիս
- Արտաքին հղումները՝ `safe_url()`-ով, `javascript:`-ը չի անցնում
- Մուտքի սահմանափակում՝ 5 փորձ րոպեում՝ ըստ հասցեի և IP-ի

## Run locally

```bash
git clone https://github.com/ani-sargsyan/ani-club.git
cd ani-club
composer install
cp .env.example .env          # APP_ENV=local, APP_DEBUG=true, DB_*
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Տեղական հաշիվներ (միայն seed-ից)՝ `ani@example.com / Ani-12345` (admin), `aram@example.com / Aram-12345` (member)։

## What I learned

Երեք նախադասություն՝ ինչն էր ամենադժվարը և ինչպես լուծեցիր։
