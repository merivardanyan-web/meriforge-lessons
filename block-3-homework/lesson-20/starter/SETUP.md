# Անիի ակումբը Laravel-ում · Դաս 20

Դաս 20-ից Laravel-ը ունի ԻՐ բազան՝ միգրացիաներով։ Հին `ani_books`-ը մնում է Դաս 09–17-ի համար։

1. HeidiSQL-ում ստեղծիր `ani_books_laravel` (utf8mb4_unicode_ci) և `.env`-ում՝ `DB_DATABASE=ani_books_laravel`
2. Պատճենիր ֆայլերը։ `0001_01_01_000000_create_users_table.php`-ը փոխարինում է Laravel-ի ստեղծածին (role-ով)
3. `php artisan migrate:fresh --seed` → 7 ժանր, 12 գիրք, 22 ընթերցանության օր, 3 անդամ
4. Տնային 3․ `php artisan migrate:rollback --step=1` → `series` սյունակը անհետանում է, `php artisan migrate` → վերադառնում է

Ստուգում tinker-ում․

```php
Book::count();                                  // 12
Book::active()->count();                        // 11
Genre::where('slug', 'patmavep')->first()->books()->count();   // 3
```
