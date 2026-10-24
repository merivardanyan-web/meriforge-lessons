<?php
/** @var array $products @var array $categories @var ?array $category @var array $filters
 *  @var string $sort @var int $total @var int $page @var int $pages @var array $query @var int $from @var int $to */
$back = urlWith('/catalog.php', $query + ['page' => $page > 1 ? $page : null]);
?>
<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <nav aria-label="Դուք այստեղ եք">
                <ol class="crumbs">
                    <li><a href="/">Գլխավոր</a></li>
                    <?php if ($category): ?>
                        <li><a href="/catalog.php">Կատալոգ</a></li>
                        <li aria-current="page"><?= e($category['name']) ?></li>
                    <?php else: ?>
                        <li aria-current="page">Կատալոգ</li>
                    <?php endif; ?>
                </ol>
            </nav>
            <h1 class="mb-10"><?= e($category['name'] ?? 'Կատալոգ') ?></h1>
            <p class="text-muted">Նոութբուքեր, մոնիտորներ, սմարթֆոններ և աքսեսուարներ՝ 2 տարվա երաշխիքով</p>
        </div>
    </div>
</section>

<section id="catalog">
    <div class="row align-start flex-wrap">

        <!-- Ֆիլտրեր՝ GET ֆորմա․ արդյունքը URL-ում — PHP Դաս 6, 10 -->
        <aside class="col w-25 w-md-100" aria-label="Ֆիլտրեր">
            <form class="filters" action="/catalog.php" method="get">
                <h2 class="title-4 mb-20">Ֆիլտրեր</h2>
                <?php if ($sort !== 'newest'): ?>
                    <input type="hidden" name="sort" value="<?= e($sort) ?>">
                <?php endif; ?>

                <div class="filters__groups">
                    <fieldset class="filters__group">
                        <legend>Կատեգորիա</legend>
                        <ul>
                            <li><label class="check"><input type="radio" name="category" value=""<?= $filters['category'] === null ? ' checked' : '' ?>> Բոլորը</label></li>
                            <?php foreach ($categories as $c): ?>
                                <li><label class="check"><input type="radio" name="category" value="<?= e($c['slug']) ?>"<?= $filters['category'] === $c['slug'] ? ' checked' : '' ?>> <?= e($c['name']) ?></label></li>
                            <?php endforeach; ?>
                        </ul>
                    </fieldset>

                    <fieldset class="filters__group">
                        <legend>Գին, ֏</legend>
                        <div class="flex gap-10">
                            <div class="field">
                                <label for="min" class="field__label">Սկսած</label>
                                <input id="min" name="min" type="number" min="0" step="1000" placeholder="0" class="input input--sm" value="<?= e((string) $filters['min']) ?>">
                            </div>
                            <div class="field">
                                <label for="max" class="field__label">Մինչև</label>
                                <input id="max" name="max" type="number" min="0" step="1000" placeholder="600000" class="input input--sm" value="<?= e((string) $filters['max']) ?>">
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="filters__group">
                        <legend>Ցուցադրել</legend>
                        <ul>
                            <?php foreach (STOCK_FILTERS as $value => $label): ?>
                                <li><label class="check"><input type="radio" name="stock" value="<?= e($value) ?>"<?= $filters['stock'] === $value ? ' checked' : '' ?>> <?= e($label) ?></label></li>
                            <?php endforeach; ?>
                        </ul>
                    </fieldset>
                </div>

                <input type="hidden" name="q" value="<?= e($filters['q']) ?>">
                <div class="flex gap-10 flex-wrap">
                    <button type="submit" class="btn btn--primary">Կիրառել</button>
                    <a href="/catalog.php" class="btn btn--ghost">Մաքրել</a>
                </div>
            </form>
        </aside>

        <div class="col w-75 w-md-100">

            <div class="toolbar flex justify-between align-center flex-wrap gap-10">
                <p class="text-muted" role="status">
                    <?php if ($total > 0): ?>
                        Ցուցադրված է <?= $from ?>–<?= $to ?>՝ <?= $total ?> ապրանքից
                    <?php else: ?>
                        Ոչինչ չգտնվեց
                    <?php endif; ?>
                </p>

                <form class="flex align-center flex-wrap gap-10" action="/catalog.php" method="get" role="search">
                    <?php foreach (['category', 'min', 'max', 'stock'] as $key): ?>
                        <?php if ($query[$key] !== null && $query[$key] !== 'all'): ?>
                            <input type="hidden" name="<?= $key ?>" value="<?= e((string) $query[$key]) ?>">
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <label for="q" class="visually-hidden">Որոնել</label>
                    <input id="q" name="q" type="search" class="input input--sm toolbar__search" placeholder="Որոնել…" value="<?= e($filters['q']) ?>" maxlength="100">

                    <label for="sort" class="field__label">Դասավորել</label>
                    <select id="sort" name="sort" class="input input--sm">
                        <?php foreach (SORT_MAP as $key => [$label]): ?>
                            <option value="<?= e($key) ?>"<?= $sort === $key ? ' selected' : '' ?>><?= e($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn btn--ghost btn--sm">Կիրառել</button>
                </form>
            </div>

            <?php if ($products === []): ?>
                <div class="empty-state">
                    <p><?= $filters['q'] !== '' ? '«' . e($filters['q']) . '» հարցմամբ' : 'Այս ֆիլտրերով' ?> ապրանք չկա։</p>
                    <a href="/catalog.php" class="btn btn--primary">Մաքրել ֆիլտրերը</a>
                </div>
            <?php else: ?>
                <div class="grid-auto gap-20">
                    <?php foreach ($products as $p): ?>
                        <?= render('partials/card', ['p' => $p, 'back' => $back]) ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?= render('partials/pagination', ['page' => $page, 'pages' => $pages, 'query' => $query, 'path' => '/catalog.php']) ?>
        </div>
    </div>
</section>
