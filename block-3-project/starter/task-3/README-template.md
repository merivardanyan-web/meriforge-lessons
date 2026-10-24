# TechnoMarket

An Armenian-language electronics store built with Laravel — after first building
the same app in plain PHP and on a hand-written MVC framework.

**Live demo:** https://…
**Demo account:** demo@… / … (manager role: can add and edit products, cannot delete)

![Catalog and admin panel](docs/screenshot.png)

## Features

- Catalog with categories, search, price filter, sorting and pagination (query string)
- Session cart with fetch-based "Buy" and a no-JS fallback (JSON API, 422 errors)
- Checkout in a database transaction with stock checks
- Admin: product CRUD, image upload, soft deletes with restore, order statuses
- Login and registration with Laravel Fortify, rate-limited login
- Roles with Gates: admin / manager / customer
- Armenian validation messages

## What I added beyond the course

- …

## Tech stack

PHP 8.3 · Laravel · MySQL · Blade · Fortify · vanilla JS (ES modules) · custom CSS (no frameworks)

## Security decisions

- `role` is not mass-assignable; registration always creates `customer`
- Deleting products is checked in the controller (`Gate::authorize`), not only hidden in Blade
- Sorting uses a whitelist; Eloquent does not parameterize column names
- `{!! !!}` is used once, only with `nl2br(e(...))`
- …

## How I used AI

- …

## Run locally

```bash
composer install
cp .env.example .env && php artisan key:generate
php artisan migrate:fresh --seed
php artisan storage:link
php artisan serve
```
