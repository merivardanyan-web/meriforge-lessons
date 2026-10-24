# config/fortify.php — ինչ փոխել `php artisan fortify:install`-ից հետո

Ֆայլը ստեղծում է Fortify-ը․ փոխիր միայն այս երեք տեղը (PHP Դաս 21-ի նույն կանոնները)։

```php
// 1. Իրականում ուղղորդում է FortifyServiceProvider-ի LoginResponse-ը (ըստ դերի)
'home' => '/',

// 2. Գրանցումից հետո՝ գլխավոր էջ
'redirects' => [
    'register' => '/',
],

// 3. Միայն այն, ինչ օգտագործում ենք
'features' => [
    Features::registration(),        // միայն client դերով (CreateNewUser)
    Features::resetPasswords(),
    Features::updatePasswords(),
],
```
