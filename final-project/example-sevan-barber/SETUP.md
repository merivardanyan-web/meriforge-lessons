# Ինչպես գործարկել այս օրինակը

Այս թղթապանակում միայն **մեր** ֆայլերն են (մոտ 70), ոչ ամբողջ Laravel-ը։ Դրանք պատճենում ես նոր նախագծի վրա․

```bash
# 1. Նոր նախագիծ և Fortify — PHP Դաս 18, 21
composer create-project laravel/laravel sevan-barber
cd sevan-barber
composer require laravel/fortify
php artisan fortify:install

# 2. Պատճենիր այս թղթապանակի ֆայլերը նախագծի վրա (նույնանունները փոխարինելով)
#    app/, config/*.md-ի փոփոխությունները, database/, lang/, public/, resources/views/, routes/web.php

# 3. composer.json → "autoload" → "files": ["app/helpers.php"]
composer dump-autoload

# 4. config/fortify.php՝ config/fortify-changes.md, config/app.php՝ config/app-changes.md

# 5. Բազա
cp .env.example .env              # DB_USERNAME, DB_PASSWORD
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve
```

Seed-ի հաշիվները (միայն տեղական)․

| Դեր | Էլ. հասցե | Գաղտնաբառ |
|---|---|---|
| admin | admin@sevanbarber.test | `ADMIN_PASSWORD` կամ Admin-12345 |
| staff | aram@sevanbarber.test, levon@sevanbarber.test | Staff-12345 |
| client (demo) | demo@sevanbarber.test | `DEMO_PASSWORD` կամ Demo-12345 |
| client | mariam@example.com | Client-12345 |

Ժամերը ստեղծվում են «այսօրից»՝ 3 օր հետ և 7 օր առաջ, ուստի `migrate:fresh --seed`-ից հետո demo-ն միշտ թարմ է։
