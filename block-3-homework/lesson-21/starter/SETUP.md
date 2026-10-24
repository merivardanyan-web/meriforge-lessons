# Անիի ակումբը Laravel-ում · Դաս 21

```bash
composer require laravel/fortify
php artisan fortify:install          # ստեղծում է config/fortify.php, FortifyServiceProvider, Actions/
php artisan lang:publish             # lang/en/ → պատճենիր lang/hy/ այս թղթապանակից
php artisan storage:link             # public/storage → storage/app/public (շապիկների համար, տնային 5)
php artisan migrate:fresh --seed
```

1. `.env`-ում՝ `APP_LOCALE=hy`, `APP_FALLBACK_LOCALE=en`
2. `config/fortify.php`-ում փոխիր այն, ինչ գրված է `config/fortify-changes.md`-ում
3. `app/Providers/FortifyServiceProvider.php`-ը և `app/Actions/Fortify/CreateNewUser.php`-ը փոխարինիր այս թղթապանակի ֆայլերով
4. Մուտք․ `ani@example.com / Ani-12345` (admin), `lilit@example.com / Lilit-12345` (editor), `aram@example.com / Aram-12345` (member)

Ստուգում․ Արամը `/admin/books`-ում ստանում է 403, Լիլիթը տեսնում է ադմինը, բայց «Ջնջել» կոճակ չունի։
