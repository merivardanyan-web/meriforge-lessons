<?php
declare(strict_types=1);

// Զամբյուղը սեսիայում՝ [id => քանակ] — PHP Դաս 7
// ⭐ Գինը սեսիայում ՉԵՆՔ պահում․ այն միշտ վերցնում ենք բազայից (Դաս 7-ի տնային)

function cart(): array
{
    return $_SESSION['cart'] ?? [];
}

/** $limit՝ պահեստի քանակը, որ զամբյուղում չլինի ավելին, քան կա */
function cartAdd(int $id, int $qty = 1, int $limit = MAX_QTY): void
{
    if ($qty < 1) {
        return;
    }

    $current = $_SESSION['cart'][$id] ?? 0;
    $_SESSION['cart'][$id] = min($current + $qty, MAX_QTY, $limit);     // վերին սահման ⭐
}

/** Քանակի փոփոխություն՝ +/− կամ դաշտից — PHP Դաս 7-ի տնային */
function cartSet(int $id, int $qty, int $limit = MAX_QTY): void
{
    if (!isset($_SESSION['cart'][$id])) {
        return;
    }

    if ($qty < 1) {
        cartRemove($id);
        return;
    }

    $_SESSION['cart'][$id] = min($qty, MAX_QTY, $limit);
}

function cartRemove(int $id): void
{
    unset($_SESSION['cart'][$id]);
}

function cartClear(): void
{
    unset($_SESSION['cart']);
}

function cartCount(): int
{
    return array_sum(cart());
}

/** Զամբյուղ + ապրանքները բազայից։ Ջնջված կամ անջատված ապրանքը դուրս է մնում */
function cartItems(): array
{
    $products = getProductsByIds(array_keys(cart()));
    $items = [];

    foreach (cart() as $id => $qty) {
        $product = $products[$id] ?? null;

        if ($product === null) {
            cartRemove((int) $id);      // ապրանքը այլևս չկա ⭐
            continue;
        }

        $items[] = [
            'product' => $product,
            'qty'     => $qty,
            'sum'     => $product['final_price'] * $qty,
        ];
    }

    return $items;
}

/** Ամփոփում՝ նույնը զամբյուղի և պատվերի էջերում */
function cartSummary(array $items, string $delivery = 'courier'): array
{
    $full = 0;
    $subtotal = 0;

    foreach ($items as $item) {
        $full += $item['product']['price'] * $item['qty'];
        $subtotal += $item['sum'];
    }

    if ($items === [] || $delivery === 'pickup') {
        $shipping = 0;
    } elseif ($delivery === 'region') {
        $shipping = REGION_SHIPPING;
    } else {
        $shipping = $subtotal >= FREE_SHIPPING_FROM ? 0 : REGION_SHIPPING;
    }

    return [
        'count'    => array_sum(array_column($items, 'qty')),
        'full'     => $full,
        'discount' => $full - $subtotal,
        'subtotal' => $subtotal,
        'shipping' => $shipping,
        'total'    => $subtotal + $shipping,
    ];
}
