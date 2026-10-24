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

**Ակումբի գնահատականներ։** Մուտք գործած անդամը գնահատում է գիրքը 1–5 աստղով, գրքի էջում՝ միջինը և քանակը։
`ratings` աղյուսակը ունի `UNIQUE(user_id, book_id)`, ուստի կրկնակի սեղմումը չի ստեղծում երկրորդ գնահատական,
իսկ կոնտրոլերը նախ փնտրում է անդամի գնահատականը և թարմացնում այն։ `user_id`-ն `$fillable`-ում չէ, ուստի դրվում է ուղիղ վերագրմամբ, ոչ `updateOrCreate`-ով։ Միջինը հաշվում է մեկ `AVG()` հարցումով (`loadAvg`), ոչ PHP-ում։

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

Նախագիծը կառուցել եմ երեք անգամ՝ մաքուր PHP-ով (PDO, սեսիաներ), սեփական MVC-ով (Router, Repository, CSRF)
և Laravel-ով։ Ամենադժվարը տրանզակցիան էր․ առաջին տարբերակում էջերը ավելանում էին, իսկ ընթերցանության օրը՝ ոչ։
