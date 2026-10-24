<?php
/** @var array $product @var array $related @var int $inCart */
$p = $product;
$here = urlWith('/product.php', ['slug' => $p['slug']]);
?>
<section id="product">
    <div class="row">
        <div class="col w-100">
            <nav aria-label="Դուք այստեղ եք">
                <ol class="crumbs">
                    <li><a href="/">Գլխավոր</a></li>
                    <li><a href="/catalog.php">Կատալոգ</a></li>
                    <?php if ($p['category_slug']): ?>
                        <li><a href="<?= e(urlWith('/catalog.php', ['category' => $p['category_slug']])) ?>"><?= e($p['category_name']) ?></a></li>
                    <?php endif; ?>
                    <li aria-current="page"><?= e($p['name']) ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row flex-wrap align-start">
        <div class="col w-50 w-md-100">
            <div class="gallery">
                <picture class="gallery__main ratio-3-2">
                    <img class="img-cover" src="/images/<?= e($p['image'] ?? 'placeholder.jpg') ?>"
                         alt="<?= e($p['name']) ?>" width="600" height="400">
                </picture>
            </div>
        </div>

        <div class="col w-50 w-md-100">
            <div class="wrapper">
                <?php if ($p['discount'] > 0): ?>
                    <span class="badge mb-10">-<?= $p['discount'] ?>%</span>
                <?php endif; ?>
                <h1 class="mb-10"><?= e($p['name']) ?></h1>
                <p class="text-muted mb-20">
                    Կոդ՝ TM-<?= 1000 + $p['id'] ?> ·
                    <span class="stock"><?= $p['stock'] > 0 ? 'Առկա է պահեստում' : 'Ժամանակավորապես չկա' ?></span>
                </p>

                <p class="product__price mb-20">
                    <?= formatPrice($p['final_price']) ?>
                    <?php if ($p['discount'] > 0): ?><del><?= formatPrice($p['price']) ?></del><?php endif; ?>
                </p>

                <?php if ($p['description']): ?>
                    <p class="mb-20"><?= nl2br(e($p['description'])) ?></p>
                <?php endif; ?>

                <?php if ($p['stock'] > 0): ?>
                    <form class="flex align-end gap-10 flex-wrap mb-30" method="post" action="/cart-add.php">
                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                        <input type="hidden" name="back" value="<?= e($here) ?>">
                        <div class="field">
                            <label for="qty" class="field__label">Քանակ</label>
                            <input id="qty" name="qty" type="number" value="1" min="1" max="<?= min(MAX_QTY, $p['stock']) ?>" class="input input--qty">
                        </div>
                        <button type="submit" class="btn btn--primary">Ավելացնել զամբյուղ</button>
                    </form>
                    <?php if ($inCart > 0): ?>
                        <p class="text-muted mb-20">Զամբյուղում արդեն <?= $inCart ?> հատ է․ <a href="/cart.php" class="link">բացել զամբյուղը</a></p>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="mb-30"><button type="button" class="btn btn--primary" disabled>Սպառված է</button></p>
                <?php endif; ?>

                <ul class="perks">
                    <li><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1 4h14v12H1zM15 9h4l4 4v3h-8z"/><circle cx="5.5" cy="18.5" r="2"/><circle cx="18.5" cy="18.5" r="2"/></svg> Անվճար առաքում Երևանում՝ <?= formatPrice(FREE_SHIPPING_FROM) ?>-ից</li>
                    <li><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2 4 5v6c0 5 3.4 9.4 8 11 4.6-1.6 8-6 8-11V5z"/><path d="m9 12 2 2 4-4"/></svg> 2 տարվա պաշտոնական երաշխիք</li>
                    <li><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12a9 9 0 0 1-15.5 6.3L3 16"/><path d="M3 12a9 9 0 0 1 15.5-6.3L21 8"/><path d="M21 3v5h-5M3 21v-5h5"/></svg> 14 օր վերադարձ առանց հարցերի</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section id="details">
    <div class="row flex-wrap">
        <div class="col w-60 w-md-100">
            <h2 class="section-title mb-30">Ապրանքի մասին</h2>
            <table class="table specs">
                <caption class="visually-hidden"><?= e($p['name']) ?>-ի տվյալները</caption>
                <tbody>
                    <tr><th scope="row">Համառոտ</th><td><?= e($p['specs'] ?? '—') ?></td></tr>
                    <tr><th scope="row">Կատեգորիա</th><td><?= e($p['category_name'] ?? 'Առանց կատեգորիայի') ?></td></tr>
                    <tr><th scope="row">Պահեստում</th><td><?= $p['stock'] ?> հատ</td></tr>
                    <tr><th scope="row">Խանութում է</th><td><?= e(date('d.m.Y', strtotime($p['created_at']))) ?>-ից</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php if ($related !== []): ?>
<section id="related">
    <div class="row">
        <div class="col w-100">
            <h2 class="section-title mb-30">Նման ապրանքներ</h2>
            <div class="grid-auto gap-20">
                <?php foreach ($related as $r): ?>
                    <?= render('partials/card', ['p' => $r, 'back' => $here]) ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>
