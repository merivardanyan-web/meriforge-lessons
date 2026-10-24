<?php
declare(strict_types=1);

// Կայքի քարտեզը — PHP Դաս 15, 16, 17
// /admin…-ը պաշտպանված է public/index.php-ում՝ ՄԵԿ տեղում, ոչ ամեն ուղու վրա ⭐

// ---------- Հանրային ----------
$router->get('/',                 [$homeController, 'index']);
$router->get('/catalog',          [$productController, 'index']);
$router->get('/category/{slug}',  [$productController, 'category']);
$router->get('/search',           [$productController, 'search']);
$router->get('/product/{slug}',   [$productController, 'show']);

$router->get('/contact',          [$contactController, 'form']);
$router->post('/contact',         [$contactController, 'submit']);
$router->post('/theme',           [$contactController, 'theme']);

// ---------- Զամբյուղ և պատվեր ----------
$router->get('/cart',             [$cartController, 'index']);
$router->post('/cart/add',        [$cartController, 'add']);
$router->post('/cart/update',     [$cartController, 'update']);
$router->post('/cart/remove',     [$cartController, 'remove']);

$router->get('/checkout',         [$checkoutController, 'form']);
$router->post('/checkout',        [$checkoutController, 'store']);
$router->get('/thanks/{id}',      [$checkoutController, 'thanks']);

// ---------- Մուտք և գրանցում — Դաս 16 ----------
$router->get('/login',            [$authController, 'form']);
$router->post('/login',           [$authController, 'login']);
$router->post('/logout',          [$authController, 'logout']);
$router->get('/register',         [$registerController, 'form']);
$router->post('/register',        [$registerController, 'store']);
$router->get('/account',          [$accountController, 'index']);

// ---------- Ադմին ----------
$router->get('/admin',                       [$adminProducts, 'index']);
$router->get('/admin/products/create',       [$adminProducts, 'create']);
$router->post('/admin/products',             [$adminProducts, 'store']);
$router->post('/admin/products/archive',     [$adminProducts, 'archive']);   // ⚠️ {id}-ից ԱՌԱՋ, թե չէ «archive»-ը կդառնար id
$router->get('/admin/products/{id}/edit',    [$adminProducts, 'edit']);
$router->post('/admin/products/{id}',        [$adminProducts, 'update']);
$router->get('/admin/orders',                [$adminOrders, 'index']);
$router->post('/admin/orders/{id}/status',   [$adminOrders, 'status']);

// ---------- API — Դաս 17 ----------
$router->get('/api/products',                [$productApi, 'index']);
$router->get('/api/products/{slug}',         [$productApi, 'show']);
$router->get('/api/cart',                    [$cartApi, 'index']);
$router->post('/api/cart/items',             [$cartApi, 'add']);
$router->delete('/api/cart/items/{id}',      [$cartApi, 'remove']);
