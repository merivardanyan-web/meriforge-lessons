<?php /** @var App\Models\Product[] $products @var string $q @var bool $canArchive */
use App\Models\PhysicalProduct;
?>
<section>
    <div class="row">
        <div class="col w-100">
            <div class="flex justify-between align-center flex-wrap gap-10 mb-20 mt-20">
                <h1>Ապրանքներ (<?= count($products) ?>)</h1>

                <form method="get" action="/admin" class="flex gap-10" role="search">
                    <label for="q" class="visually-hidden">Որոնել ապրանք</label>
                    <input id="q" name="q" type="search" class="input input--sm" placeholder="Անվանում…" value="<?= e($q) ?>">
                    <button class="btn btn--ghost btn--sm">Որոնել</button>
                </form>
            </div>

            <?php if (!$canArchive): ?>
                <p class="alert">Դու մենեջեր ես․ կարող ես ավելացնել և խմբագրել, իսկ արխիվացնել կարող է միայն ադմինը։</p>
            <?php endif; ?>

            <form method="post" action="/admin/products/archive" onsubmit="return confirm('Արխիվացնե՞լ նշվածները')">
                <?= csrf_field() ?>
                <div class="scroll-x">
                <table class="table admin-table">
                    <caption class="visually-hidden">Բոլոր ապրանքները, ներառյալ արխիվացվածները</caption>
                    <thead>
                        <tr>
                            <?php if ($canArchive): ?><th scope="col"><span class="visually-hidden">Նշել</span></th><?php endif; ?>
                            <th scope="col">Անվանում</th>
                            <th scope="col">Տեսակ</th>
                            <th scope="col">Կատեգորիա</th>
                            <th scope="col">Գին</th>
                            <th scope="col">Պահեստ</th>
                            <th scope="col">Կարգավիճակ</th>
                            <th scope="col"><span class="visually-hidden">Գործողություններ</span></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($products as $p): ?>
                        <tr class="<?= $p->isActive ? '' : 'row-inactive' ?>">
                            <?php if ($canArchive): ?>
                                <td><input type="checkbox" name="ids[]" value="<?= $p->id ?>" aria-label="Նշել՝ <?= e($p->name) ?>"<?= $p->isActive ? '' : ' disabled' ?>></td>
                            <?php endif; ?>
                            <td><?= e($p->name) ?></td>
                            <td><?= e($p->typeLabel()) ?></td>
                            <td><?= e($p->category?->name ?? '—') ?></td>
                            <td>
                                <?= $p->formattedPrice() ?>
                                <?php if ($p->hasDiscount()): ?><span class="text-muted text-small">−<?= $p->discount ?>%</span><?php endif; ?>
                            </td>
                            <td><?= $p instanceof PhysicalProduct ? $p->stock : '∞' ?></td>
                            <td><?= $p->isActive ? 'Ակտիվ' : 'Արխիվ' ?></td>
                            <td>
                                <div class="actions">
                                    <a href="/admin/products/<?= $p->id ?>/edit" class="btn btn--ghost btn--sm">Խմբագրել</a>
                                    <?php if ($p->isActive): ?>
                                        <a href="<?= e($p->url()) ?>" class="btn btn--ghost btn--sm">Դիտել</a>
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
                <?php elseif ($canArchive): ?>
                    <button type="submit" class="btn btn--danger btn--sm mt-20">Արխիվացնել նշվածները</button>
                <?php endif; ?>
            </form>
        </div>
    </div>
</section>
