<?php
declare(strict_types=1);

// Կայքի քարտեզը՝ մեկ ֆայլում — PHP Դաս 15
// ⚠️ Հերթականությունը կարևոր է․ /admin/books/archive-ը՝ /admin/books/{id}-ից ԱՌԱՋ

// ---------- Գրքեր ----------
$router->get('/',                      [$bookController, 'index']);
$router->get('/genre/{slug}',          [$bookController, 'genre']);      // տնային 2
$router->get('/search',                [$bookController, 'search']);     // տնային 3
$router->get('/book/{slug}',           [$bookController, 'show']);
$router->post('/book/{slug}/cover',    [$bookController, 'cover']);
$router->post('/book/{slug}/read',     [$bookController, 'read']);

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

// ---------- Ադմին (Դաս 16-ում՝ մուտքով) ----------
$router->get('/admin/books',             [$adminBooks, 'index']);
$router->get('/admin/books/create',      [$adminBooks, 'create']);
$router->post('/admin/books',            [$adminBooks, 'store']);
$router->post('/admin/books/archive',    [$adminBooks, 'archive']);
$router->get('/admin/books/{id}/edit',   [$adminBooks, 'edit']);
$router->post('/admin/books/{id}',       [$adminBooks, 'update']);
