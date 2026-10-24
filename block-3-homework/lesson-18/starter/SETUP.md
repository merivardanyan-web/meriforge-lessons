# Անիի ակումբը Laravel-ում · Դաս 18

Այս թղթապանակում են միայն **մեր** ֆայլերը։ Laravel-ը ինքը տեղադրվում է Composer-ով։

```bash
composer create-project laravel/laravel ani-club
cd ani-club
```

1. `.env`-ում միացիր Դաս 09–17-ի բազային (Դաս 20-ում կստեղծենք նորը միգրացիաներով)․

   ```
   APP_NAME="Անիի ակումբը"
   DB_CONNECTION=mysql
   DB_DATABASE=ani_books
   DB_USERNAME=root
   DB_PASSWORD=
   ```

2. Պատճենիր այս թղթապանակի ֆայլերը նույն ուղիներով (`routes/web.php`-ը փոխարինում է եղածին)։
3. `php artisan serve` → http://127.0.0.1:8000

Ստուգում․ `php artisan route:list --except-vendor`-ը պիտի ցույց տա երեք ուղի՝ `home`, `genre`, `book.show`։
