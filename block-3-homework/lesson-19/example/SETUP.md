# Անիի ակումբը Laravel-ում · Դաս 19

Դաս 18-ի նախագծին ավելացրու այս թղթապանակի ֆայլերը։

1. `composer.json`-ում ավելացրու helpers-ը (տնային 6) և թարմացրու autoload-ը․

   ```json
   "autoload": {
       "psr-4": { "App\\": "app/" },
       "files": ["app/helpers.php"]
   }
   ```

   ```bash
   composer dump-autoload
   ```

2. Կոմպոնենտները ստեղծված են `--view`-ով (դաս չունեն)․ `php artisan make:component BookCard --view`
3. Ադմինի կոնտրոլերը՝ `php artisan make:controller Admin/BookController --resource`
4. `php artisan route:list --except-vendor`

⚠️ Ադմինը դեռ բաց է բոլորի համար։ Մուտքը և Gate-երը՝ Դաս 21-ում։
