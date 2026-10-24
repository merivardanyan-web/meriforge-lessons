<?php /** @var array $orders */ ?>
<section>
    <div class="row">
        <div class="col w-100">
            <h1 class="mb-20">Պատվերներ (<?= count($orders) ?>)</h1>

            <?php if ($orders === []): ?>
                <p class="text-muted">Պատվերներ դեռ չկան։</p>
            <?php else: ?>
            <div class="scroll-x">
            <table class="table admin-table">
                <caption class="visually-hidden">Բոլոր պատվերները՝ նորերը վերևում</caption>
                <thead>
                    <tr>
                        <th scope="col">№</th>
                        <th scope="col">Գնորդ</th>
                        <th scope="col">Տողեր / հատ</th>
                        <th scope="col">Գումար</th>
                        <th scope="col">Կարգավիճակ</th>
                        <th scope="col">Ամսաթիվ</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($orders as $o): ?>
                    <?php $total = (int) $o['total']; ?>
                    <tr>
                        <td>TM-<?= 20000 + (int) $o['id'] ?></td>
                        <td><?= e($o['customer_name']) ?><br><span class="text-muted text-small"><?= e($o['phone']) ?></span></td>
                        <td><?= (int) $o['line_count'] ?> / <?= (int) $o['units'] ?></td>
                        <td><?= formatPrice($total) ?></td>
                        <td><span class="badge"><?= e(ORDER_STATUS[$o['status']] ?? $o['status']) ?></span></td>
                        <td><?= e(date('d.m.Y H:i', strtotime($o['created_at']))) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
