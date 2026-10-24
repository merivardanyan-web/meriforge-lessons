# ՏեխնոՄարկետ · Laravel (Բլոկ 3 · Առաջադրանք 3)

Առաջադրանք 2-ի խանութը՝ Laravel-ով։ Նույն էջերը, նույն CSS-ը, նույն կանոնները, բայց
Router-ի, Repository-ների, Validator-ի և Auth-ի փոխարեն՝ ֆրեյմվորքի գործիքները (PHP Դաս 18–21)։

Այս թղթապանակում **միայն մեր ֆայլերն են**, ոչ ամբողջ Laravel-ը (vendor-ը 70+ ՄԲ է)։
Դրանք պատճենում ես նոր նախագծի վրա․

## Տեղադրում

```bash
# 1. Նոր նախագիծ — PHP Դաս 18
composer create-project laravel/laravel technomarket-laravel
cd technomarket-laravel

# 2. Fortify — PHP Դաս 21 (ստեղծում է config/fortify.php, FortifyServiceProvider, Actions/Fortify)
composer require laravel/fortify
php artisan fortify:install

# 3. Պատճենիր այս թղթապանակի ֆայլերը նախագծի վրա (փոխարինելով նույնանունները)
#    app/, database/, resources/views/, routes/web.php, lang/hy/, public/

# 4. composer.json → "autoload" բաժնում ավելացրու helpers-ը — PHP Դաս 19-ի տնային
#    "files": ["app/helpers.php"]
composer dump-autoload

# 5. config/fortify.php-ում՝ config/fortify-changes.md-ի երեք փոփոխությունը

# 6. Բազա և տվյալներ
cp .env.example .env            # լրացրու DB_USERNAME, DB_PASSWORD
php artisan key:generate
php artisan migrate:fresh --seed
php artisan storage:link        # ադմինից վերբեռնված նկարների համար

php artisan serve               # կամ Laragon → http://technomarket-laravel.test
```

Demo հաշիվները՝ նույնը, ինչ Առաջադրանք 2-ում (միայն `APP_ENV=local`)․
`admin@technomarket.test / Admin-12345`, `manager@technomarket.test / Manager-12345`, `anna@example.com / Anna-12345`։

## Ֆայլերը՝ Առաջադրանք 2-ի հետ համեմատած

| Առաջադրանք 2 | Այստեղ | Դաս |
|---|---|---|
| `routes.php` + `Router` | `routes/web.php` | 18 |
| `views/layout.php`, `partials/card.php` | `layouts/app.blade.php`, `<x-product-card>`, `<x-alert>`, `<x-field>` | 19 |
| `share('cart', ...)` | `View::composer('partials.header')` | 19 |
| `new CartController($products, $cart)` | autowiring կոնստրուկտորում | 19 |
| `sql/schema.sql` | `database/migrations/` | 20 |
| `sql/seed.sql` | `DatabaseSeeder`, `ProductFactory` | 20 |
| `ProductRepository` | `Product` մոդել՝ `scopeActive`, `scopeFilter` | 20 |
| `Product::toArray()` | `ProductResource` | 20 |
| `archive()` | `SoftDeletes`, «Ջնջվածներ» էջ, `restore()` | 20 |
| `Validator::product()` | `ProductRequest` | 21 |
| `ImageUploader` | `'image', 'mimes', 'max'` + `->store('products', 'public')` | 21 |
| `Auth`, `LoginThrottle`, `RegisterController` | Fortify, `RateLimiter::for('login')`, `CreateNewUser` | 21 |
| `public/index.php`-ի `/admin` ստուգումը | `middleware(['auth', 'can:manage-products'])` | 21 |
| `isAdmin()` կոնտրոլերում | `Gate::authorize('delete-products')` | 21 |
| `csrf_field()` | `@csrf` | 18 |

## Անվտանգության որոշումներ

- `role`-ը `$fillable`-ում չէ․ դերը դրվում է միայն ուղիղ վերագրումով (seeder, tinker)
- `Model::shouldBeStrict()`՝ N+1-ը, անհայտ սյունակը և լուռ անտեսված դաշտը սխալ են զարգացման ժամանակ
- Դասավորումը՝ սպիտակ ցուցակով (`ProductController::SORTS`), Eloquent-ը սյունակի անունը չի պարամետրացնում
- Ջնջումը պաշտպանված է կոնտրոլերում (`Gate::authorize`), ոչ միայն `@can`-ով
- `{!! !!}`՝ միայն մեկ տեղում՝ `nl2br(e(...))`, որտեղ նախ փախցնում ենք
- Մուտքից հետո աշխատակիցը գնում է ադմին, գնորդը՝ գլխավոր էջ (`LoginResponse`)։
  Միայն `config('fortify.home')`-ով գնորդը կհայտնվեր `/admin/products`-ում և կստանար 403

## Ստուգում

Այս ֆայլերը պատճենվել են մաքուր Laravel 12 նախագծի վրա (Fortify, MariaDB), կատարվել են
`migrate:fresh --seed`, `storage:link`, `view:cache`, և կայքը ստուգվել է բրաուզերում 45 ավտոմատ
ստուգումով․ կատալոգ և էջավորում, զամբյուղ fetch-ով, պատվեր տրանզակցիայով, նկարի վերբեռնում,
soft delete և վերականգնում, դերեր և 403, CSRF 419, API, գրանցում, մուտքի սահմանափակում (429)։
Քո մեքենայում միևնույն է անցիր առաջադրանքի ընդունման ստուգաթերթով (task-3.html, բաժին 05)։
