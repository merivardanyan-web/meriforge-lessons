# config/fortify.php — ինչ փոխել `php artisan fortify:install`-ից հետո (PHP Դաս 21)

Ֆայլը ստեղծում է Fortify-ը, ամբողջը չենք պատճենում․ փոխիր միայն այս երեք տեղը։

```php
// 1. Լռելյայն վերահղումը։ Իրականում մուտքից հետո ուղղորդում է FortifyServiceProvider-ի
//    LoginResponse-ը (admin և editor՝ ադմին, անդամը՝ գլխավոր), ուստի այստեղ՝ '/'
'home' => '/',

// 2. Գրանցումից հետո՝ գլխավոր էջ, ոչ ադմին վահանակ
'redirects' => [
    'register' => '/',
],

// 3. Միայն այն, ինչ օգտագործում ենք
'features' => [
    Features::registration(),        // ակումբի անդամների համար՝ member դերով (Դաս 16)
    Features::resetPasswords(),
    Features::updatePasswords(),
    // Features::emailVerification(),
    // Features::twoFactorAuthentication(...),
],

// 'views' => true մնում է․ մեր շաբլոնները նշված են FortifyServiceProvider-ում
```

`bootstrap/providers.php`-ում պետք է լինի `App\Providers\FortifyServiceProvider::class`․
`fortify:install`-ը այն ավելացնում է ինքը։ Եթե `/login`-ը 404 է, ստուգիր այստեղ։
