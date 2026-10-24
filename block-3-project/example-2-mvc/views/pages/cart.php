<?php /** @var App\Models\CartItem[] $items  @var App\Services\CartSummary $summary */
use App\Services\CartSummary;
?>
<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <nav aria-label="Դուք այստեղ եք">
                <ol class="crumbs">
                    <li><a href="/">Գլխավոր</a></li>
                    <li aria-current="page">Զամբյուղ</li>
                </ol>
            </nav>
            <h1 class="mb-10">Զամբյուղ</h1>
            <p class="text-muted"><?= $summary->count ?> ապրանք</p>
        </div>
    </div>
</section>

<section id="cart">
    <?php if ($items === []): ?>
        <div class="row"><div class="col w-100">
            <div class="empty-state">
                <p>Զամբյուղը դատարկ է։</p>
                <a href="/catalog" class="btn btn--primary">Անցնել կատալոգ</a>
            </div>
        </div></div>
    <?php else: ?>
    <div class="row flex-wrap">
        <div class="col w-70 w-md-100">
            <table class="table cart-table mb-20">
                <caption class="visually-hidden">Զամբյուղի ապրանքները</caption>
                <thead>
                    <tr>
                        <th scope="col">Ապրանք</th>
                        <th scope="col">Գին</th>
                        <th scope="col">Քանակ</th>
                        <th scope="col">Գումար</th>
                        <th scope="col"><span class="visually-hidden">Գործողություն</span></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($items as $item): ?>
                    <?php $p = $item->product; $qid = 'q' . $p->id; ?>
                    <tr>
                        <td>
                            <div class="cart-table__product">
                                <picture class="ratio-1-1">
                                    <img class="img-cover" src="<?= e($p->imageUrl()) ?>" alt="" width="600" height="400" loading="lazy">
                                </picture>
                                <div>
                                    <a href="<?= e($p->url()) ?>"><?= e($p->name) ?></a>
                                    <p class="card__meta"><?= e($p->specs ?? '') ?></p>
                                </div>
                            </div>
                        </td>
                        <td><span class="cell-label">Գին</span> <span><?= $p->formattedPrice() ?>
                            <?php if ($p->hasDiscount()): ?><del class="text-small text-muted"><?= $p->formattedOldPrice() ?></del><?php endif; ?></span></td>
                        <td>
                            <span class="cell-label" aria-hidden="true">Քանակ</span>
                            <!-- −/+ և դաշտը՝ մեկ ֆորմա։ Enter-ը սեղմում է ԱՌԱՋԻՆ submit կոճակը, ուստի «set»-ը առաջինն է։
                                 novalidate․ 50-ը սահմանափակում է սերվերը, ոչ բրաուզերը -->
                            <form method="post" action="/cart/update" class="qty-form" novalidate>
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= $p->id ?>">
                                <button type="submit" name="action" value="set" class="visually-hidden" tabindex="-1">Թարմացնել</button>
                                <button type="submit" name="action" value="dec" class="qty-btn" aria-label="Պակասեցնել՝ <?= e($p->name) ?>">−</button>
                                <label for="<?= $qid ?>" class="visually-hidden">Քանակ՝ <?= e($p->name) ?></label>
                                <input id="<?= $qid ?>" name="qty" type="number" value="<?= $item->qty ?>" min="1" max="<?= $p->maxQty() ?>" class="input input--sm input--qty">
                                <button type="submit" name="action" value="inc" class="qty-btn" aria-label="Ավելացնել՝ <?= e($p->name) ?>"<?= $item->qty >= $p->maxQty() ? ' disabled' : '' ?>>+</button>
                            </form>
                        </td>
                        <td><span class="cell-label">Գումար</span> <strong><?= formatPrice($item->sum()) ?></strong></td>
                        <td>
                            <form method="post" action="/cart/remove">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= $p->id ?>">
                                <button type="submit" class="link-danger">Հեռացնել<span class="visually-hidden"> <?= e($p->name) ?></span></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <div class="cart-actions flex justify-between flex-wrap gap-10">
                <a href="/catalog" class="btn btn--ghost">← Շարունակել գնումները</a>
                <form method="post" action="/cart/remove" onsubmit="return confirm('Դատարկե՞լ զամբյուղը')">
                    <?= csrf_field() ?>
                    <input type="hidden" name="all" value="1">
                    <button type="submit" class="btn btn--ghost">Դատարկել զամբյուղը</button>
                </form>
            </div>
        </div>

        <div class="col w-30 w-md-100">
            <aside class="summary" aria-labelledby="summary-title">
                <h2 id="summary-title" class="title-4 mb-20">Ամփոփում</h2>
                <?= render('partials/summary', ['summary' => $summary]) ?>
                <a href="/checkout" class="btn btn--primary w-100 mt-20">Ձևակերպել պատվերը</a>
                <p class="field__hint mt-10">Առաքումը Երևանում անվճար է <?= formatPrice(CartSummary::FREE_FROM) ?>-ից</p>
            </aside>
        </div>
    </div>
    <?php endif; ?>
</section>
