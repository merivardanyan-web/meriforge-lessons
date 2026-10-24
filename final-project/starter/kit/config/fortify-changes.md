# config/fortify.php — ինչ փոխել `php artisan fortify:install`-ից հետո (PHP Դաս 21)

```php
// 1. Մուտքից հետո ուղղորդումը՝ FortifyServiceProvider-ի LoginResponse-ում ըստ դերի, այստեղ՝ '/'
'home' => '/',

// 2. Միայն այն, ինչ օգտագործում ես
'features' => [
    Features::registration(),        // միայն եթե թեման պահանջում է, և միայն ամենացածր դերով
    Features::resetPasswords(),
    Features::updatePasswords(),
],
```

`FortifyServiceProvider`-ում՝ `Fortify::loginView()`, `Fortify::registerView()` և `RateLimiter::for('login')`։
Օրինակը՝ `example-sevan-barber/app/Providers/FortifyServiceProvider.php`։
