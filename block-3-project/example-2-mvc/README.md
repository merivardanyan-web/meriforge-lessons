# ՏեխնոՄարկետ · սեփական MVC (Բլոկ 3 · Առաջադրանք 2)

Առաջադրանք 1-ի խանութը՝ դասերով, Composer-ով, մեկ մուտքով, մուտքով/գրանցումով և JSON API-ով։
Կոդը օգտագործում է PHP Դաս 1–17-ը։ Կառուցվածքը նույնն է, ինչ Laravel-ինը (Դաս 18)։

## Գործարկում

```bash
composer install                     # vendor/ և autoload — PHP Դաս 14
cp .env.example .env                 # լրացրու DB_USER, DB_PASS
mysql -u root < sql/schema.sql
mysql -u root < sql/seed.sql
php -S localhost:8000 -t public public/index.php
```

**Laragon**․ Menu → Apache → `sites-enabled` → DocumentRoot-ը՝ `…/technomarket/public` (PHP Դաս 15)։

## Demo հաշիվներ (միայն լոկալ)

| Էլ. հասցե | Գաղտնաբառ | Դեր | Կարող է |
|---|---|---|---|
| admin@technomarket.test | Admin-12345 | admin | ամեն ինչ, ներառյալ արխիվացում |
| manager@technomarket.test | Manager-12345 | manager | ավելացնել, խմբագրել, պատվերների կարգավիճակ |
| anna@example.com | Anna-12345 | customer | գնել, տեսնել իր պատվերները |

## Կառուցվածք

```
public/index.php        ← միակ մուտքը․ CSRF, /admin-ի պաշտպանություն, HTML կամ JSON սխալներ
bootstrap.php           ← .env, սեսիա, PDO, Repository-ներ, կոնտրոլերներ
routes.php              ← կայքի քարտեզը
src/Http/               ← Request, Response, Router (405՝ սխալ մեթոդի դեպքում)
src/Models/             ← Product (abstract), PhysicalProduct, DigitalProduct, Shippable, Category, User, CartItem
src/Repositories/       ← ամբողջ SQL-ը
src/Services/           ← Cart, CartSummary, Auth, LoginThrottle, Validator, ImageUploader
src/Controllers/        ← ամեն էջ՝ մեթոդ, Admin/ և Api/
views/                  ← նույն շաբլոնները, ինչ Առաջադրանք 1-ում, բայց օբյեկտներով
public/js/              ← progressive enhancement․ fetch-ով «Գնել» և որոնում գրելու ընթացքում
```

## API

| Մեթոդ | Ուղի | Պատասխան |
|---|---|---|
| GET | `/api/products?q=&category=&sort=&page=&per_page=` | `{ data: [...], meta: { page, per_page, total, last_page } }` |
| GET | `/api/products/{slug}` | `{ data: {...} }` կամ 404 |
| GET | `/api/cart` | տողերը և ամփոփումը |
| POST | `/api/cart/items` `{ "id": 1, "qty": 2 }` | 200, 404, 419 (առանց token-ի), 422 (սխալ քանակ) |
| DELETE | `/api/cart/items/{id}` | 200, 419 |

Վիճակ փոխող հարցումները պահանջում են `X-CSRF-Token` վերնագիր (արժեքը՝ `<meta name="csrf-token">`-ից)։

## Անվտանգության որոշումներ

- CSRF՝ ամեն POST/DELETE-ի համար մեկ տեղում (`public/index.php`), ֆորմայից կամ վերնագրից (Դաս 16, 17)
- `/admin`-ը պաշտպանված է ըստ ուղու նախածանցի, ոչ ամեն ուղու վրա առանձին (Դաս 16)
- Մենեջերը չի կարող արխիվացնել նույնիսկ ճիշտ token-ով․ ստուգումը կոնտրոլերում է, ոչ միայն շաբլոնում
- Գրանցումը վերցնում է միայն `name` և `email`․ `role`-ը DevTools-ով ավելացնելը ոչինչ չի փոխում
- Մուտքի սխալը նույնն է՝ անհայտ հասցեի և սխալ գաղտնաբառի դեպքում, `password_verify`-ը կանչվում է միշտ
- `session_regenerate_id()` մուտքից հետո, ելքը՝ POST
- `toArray()`-ը բացահայտ ցանկ է․ API-ն չի ցույց տալիս պահեստի քանակը
- Պատվերի էջը՝ միայն իր սեսիայում կամ իր հաշվում (`/thanks/1, 2, 3…`-ով ուրիշինը չի երևում)

## Ինչ է փոխվում Laravel-ում (Առաջադրանք 3)

| Այստեղ | Laravel |
|---|---|
| `routes.php` + `Router` | `routes/web.php`, `Route::resource` |
| `bootstrap.php`-ում `new Controller(...)` | autowiring |
| `views/*.php` + `e()` | Blade `{{ }}` |
| `ProductRepository` + SQL | Eloquent մոդելներ և միգրացիաներ |
| `Validator::product()` | `ProductRequest` |
| `Auth`, `LoginThrottle`, CSRF | Fortify, `throttle`, `@csrf` |
| `isAdmin()` կոնտրոլերում | `Gate::authorize('delete-products')` |
