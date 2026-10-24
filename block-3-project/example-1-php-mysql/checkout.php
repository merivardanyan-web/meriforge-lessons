<?php
declare(strict_types=1);

// Պատվերի ձևակերպում — PHP Դաս 6 (վալիդացիա, PRG) + Դաս 11 (տրանզակցիա)

require_once __DIR__ . '/bootstrap.php';

$items = cartItems();

if ($items === []) {
    flash('error', 'Զամբյուղը դատարկ է');
    redirect('/cart.php');
}

$errors = [];
$old = ['delivery' => 'courier', 'payment' => 'cash', 'region' => 'Երևան'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = validateCheckout($_POST);
    $summary = cartSummary($items, (string) ($_POST['delivery'] ?? 'courier'));

    if ($errors === []) {
        try {
            $orderId = createOrder($_POST, $items, $summary);

            cartClear();
            $_SESSION['last_order'] = $orderId;     // ⭐ thanks.php-ն ցույց կտա միայն ՔՈ պատվերը

            redirect('/thanks.php?order=' . $orderId);
        } catch (RuntimeException $e) {
            // Սպասված սխալ՝ ապրանքը սպառվեց, մինչ լրացնում էիր ֆորման
            $errors['_form'] = $e->getMessage();
        }
    }

    $old = $_POST;
}

renderPage('pages/checkout', [
    'items'   => $items,
    'summary' => cartSummary($items, (string) ($old['delivery'] ?? 'courier')),
    'errors'  => $errors,
    'old'     => $old,
], 'Պատվերի ձևակերպում — ' . SITE_NAME, 'cart');
