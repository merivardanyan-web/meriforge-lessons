<?php /** @var array $order */ ?>
<section class="status">
    <div class="row">
        <div class="col w-100">
            <div class="wrapper">
                <span class="status__icon"><svg viewBox="0 0 24 24" fill="none" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg></span>
                <h1 class="mb-10">Շնորհակալություն, <?= e($order['customer_name']) ?>, պատվերն ընդունված է</h1>
                <p class="text-muted mb-10">Պատվերի համարը՝ <strong>TM-<?= 20000 + (int) $order['id'] ?></strong></p>

                <ul class="order-items">
                    <?php foreach ($order['items'] as $line): ?>
                        <li><?= e($line['name']) ?> × <?= $line['quantity'] ?> — <?= formatPrice($line['line_total']) ?></li>
                    <?php endforeach; ?>
                    <?php if ($order['shipping'] > 0): ?>
                        <li>Առաքում — <?= formatPrice($order['shipping']) ?></li>
                    <?php endif; ?>
                </ul>
                <p class="mb-30"><strong>Ընդամենը՝ <?= formatPrice($order['total']) ?></strong>։ Կզանգահարենք <?= e($order['phone']) ?> համարին 30 րոպեի ընթացքում։</p>

                <div class="flex gap-10 flex-wrap justify-center">
                    <a href="/" class="btn btn--primary">Գլխավոր էջ</a>
                    <a href="/catalog.php" class="btn btn--ghost">Շարունակել գնումները</a>
                </div>
            </div>
        </div>
    </div>
</section>
