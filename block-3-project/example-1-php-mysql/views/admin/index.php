<?php /** @var array $products @var array $stats @var string $q */ ?>
<section>
    <div class="row">
        <div class="col w-100">
            <div class="flex justify-between align-center flex-wrap gap-10 mb-20">
                <h1>Ապրանքներ (<?= count($products) ?>)</h1>

                <form method="get" action="/admin/index.php" class="flex gap-10" role="search">
                    <label for="q" class="visually-hidden">Որոնել ապրանք</label>
                    <input id="q" name="q" type="search" class="input input--sm" placeholder="Անվանում…" value="<?= e($q) ?>">
                    <button class="btn btn--ghost btn--sm">Որոնել</button>
                </form>
            </div>

            <ul class="admin-stats flex flex-wrap gap-10 mb-20">
                <?php foreach ($stats as $s): ?>
                    <li><strong><?= e($s['name']) ?></strong> · <?= (int) $s['products'] ?> ապրանք, <?= (int) $s['units'] ?> հատ</li>
                <?php endforeach; ?>
            </ul>

            <!-- Մեկ ֆորմա՝ զանգվածային արխիվացման համար, checkbox-ները՝ ids[] — PHP Դաս 11-ի տնային -->
            <form method="post" action="/admin/delete.php" onsubmit="return confirm('Արխիվացնե՞լ նշվածները')">
                <div class="scroll-x">
                <table class="table admin-table">
                    <caption class="visually-hidden">Բոլոր ապրանքները, ներառյալ արխիվացվածները</caption>
                    <thead>
                        <tr>
                            <th scope="col"><span class="visually-hidden">Նշել</span></th>
                            <th scope="col">Անվանում</th>
                            <th scope="col">Կատեգորիա</th>
                            <th scope="col">Գին</th>
                            <th scope="col">Պահեստ</th>
                            <th scope="col">Կարգավիճակ</th>
                            <th scope="col"><span class="visually-hidden">Գործողություններ</span></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($products as $p): ?>
                        <tr class="<?= $p['is_active'] ? '' : 'row-inactive' ?>">
                            <td><input type="checkbox" name="ids[]" value="<?= $p['id'] ?>" aria-label="Նշել՝ <?= e($p['name']) ?>"<?= $p['is_active'] ? '' : ' disabled' ?>></td>
                            <td><?= e($p['name']) ?></td>
                            <td><?= e($p['category_name'] ?? '—') ?></td>
                            <td>
                                <?= formatPrice($p['final_price']) ?>
                                <?php if ($p['discount'] > 0): ?><span class="text-muted text-small">−<?= $p['discount'] ?>%</span><?php endif; ?>
                            </td>
                            <td><?= $p['stock'] ?></td>
                            <td><?= $p['is_active'] ? 'Ակտիվ' : 'Արխիվ' ?></td>
                            <td>
                                <div class="actions">
                                    <a href="/admin/form.php?id=<?= $p['id'] ?>" class="btn btn--ghost btn--sm">Խմբագրել</a>
                                    <?php if ($p['is_active']): ?>
                                        <a href="<?= e(urlWith('/product.php', ['slug' => $p['slug']])) ?>" class="btn btn--ghost btn--sm">Դիտել</a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                </div>

                <?php if ($products === []): ?>
                    <p class="text-muted mt-20">Ոչինչ չգտնվեց։</p>
                <?php else: ?>
                    <button type="submit" class="btn btn--danger btn--sm mt-20">Արխիվացնել նշվածները</button>
                <?php endif; ?>
            </form>
        </div>
    </div>
</section>
