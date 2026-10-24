<?php /** @var array $orders @var array $stats */
use App\Repositories\OrderRepository;
?>
<section>
    <div class="row">
        <div class="col w-100">
            <h1 class="mb-20 mt-20">Պատվերներ (<?= count($orders) ?>)</h1>

            <ul class="admin-stats flex flex-wrap gap-10 mb-20">
                <?php foreach ($stats as $s): ?>
                    <li><strong><?= e($s['name']) ?></strong> · <?= (int) $s['products'] ?> ապրանք, <?= (int) $s['units'] ?> հատ պահեստում</li>
                <?php endforeach; ?>
            </ul>

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
                        <td>
                            <form method="post" action="/admin/orders/<?= (int) $o['id'] ?>/status" class="flex gap-10">
                                <?= csrf_field() ?>
                                <label for="status-<?= (int) $o['id'] ?>" class="visually-hidden">Կարգավիճակ</label>
                                <select id="status-<?= (int) $o['id'] ?>" name="status" class="input input--sm">
                                    <?php foreach (OrderRepository::STATUS as $key => $label): ?>
                                        <option value="<?= e($key) ?>"<?= $o['status'] === $key ? ' selected' : '' ?>><?= e($label) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button class="btn btn--ghost btn--sm">Պահել</button>
                            </form>
                        </td>
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
