<?php
declare(strict_types=1);

// Կայքի քարտեզը՝ մեկ ֆայլում — PHP Դաս 15, 16, 17
// ⚠️ /admin…-ը պաշտպանված է public/index.php-ում՝ ՄԵԿ տեղում, ոչ ամեն ուղու վրա (տնային 8)

// ---------- Գրքեր ----------
$router->get('/',                      [$bookController, 'index']);
$router->get('/genre/{slug}',          [$bookController, 'genre']);
$router->get('/search',                [$bookController, 'search']);
$router->get('/book/{slug}',           [$bookController, 'show']);

// ---------- Կարդալու ցուցակ ----------
$router->get('/list',                  [$listController, 'index']);
$router->post('/list/add',             [$listController, 'add']);
$router->post('/list/remove',          [$listController, 'remove']);
$router->post('/list/goal',            [$listController, 'goal']);

// ---------- Էջեր ----------
$router->get('/about',                 [$pageController, 'about']);
$router->get('/suggest',               [$pageController, 'suggestForm']);
$router->post('/suggest',              [$pageController, 'suggest']);
$router->post('/theme',                [$pageController, 'theme']);

// ---------- Մուտք և գրանցում — Դաս 16 ----------
$router->get('/login',                 [$authController, 'form']);
$router->post('/login',                [$authController, 'login']);
$router->post('/logout',               [$authController, 'logout']);
$router->get('/register',              [$registerController, 'form']);
$router->post('/register',             [$registerController, 'store']);

// ---------- Ադմին (admin, editor) ----------
// Դաս 16․ շապիկը և «Կարդացի այսօր»-ը տեղափոխվեցին /admin/-ի տակ, որ նույն կանոնը պաշտպանի դրանք
$router->post('/admin/book/{slug}/cover',  [$bookController, 'cover']);
$router->post('/admin/book/{slug}/read',   [$bookController, 'read']);

$router->get('/admin/books',             [$adminBooks, 'index']);
$router->get('/admin/books/create',      [$adminBooks, 'create']);
$router->post('/admin/books',            [$adminBooks, 'store']);
$router->post('/admin/books/archive',    [$adminBooks, 'archive']);      // միայն admin (տնային 7)
$router->get('/admin/books/{id}/edit',   [$adminBooks, 'edit']);
$router->post('/admin/books/{id}',       [$adminBooks, 'update']);

// ---------- API — Դաս 17 ----------
$router->get('/api/books',               [$bookApi, 'index']);
$router->get('/api/books/{slug}',        [$bookApi, 'show']);
$router->get('/api/list',                [$listApi, 'index']);
$router->post('/api/list/items',         [$listApi, 'add']);
$router->delete('/api/list/items/{id}',  [$listApi, 'remove']);
