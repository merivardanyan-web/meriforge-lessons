<?php
/** @var App\Models\Product[] $products @var ?App\Models\Category $category @var array $filters @var string $sort
 *  @var int $total @var int $page @var int $pages @var array $query @var string $path @var App\Models\Category[] $categories */
use App\Repositories\ProductRepository;

$back = url($path, $query + ['page' => $page > 1 ? $page : null]);
$from = $total === 0 ? 0 : ($page - 1) * ProductRepository::PER_PAGE + 1;
$to   = min($total, $page * ProductRepository::PER_PAGE);
?>
<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <nav aria-label="Դուք այստեղ եք">
                <ol class="crumbs">
                    <li><a href="/">Գլխավոր</a></li>
                    <?php if ($category): ?>
                        <li><a href="/catalog">Կատալոգ</a></li>
                        <li aria-current="page"><?= e($category->name) ?></li>
                    <?php else: ?>
                        <li aria-current="page">Կատալոգ</li>
                    <?php endif; ?>
                </ol>
            </nav>
            <h1 class="mb-10"><?= e($category->name ?? 'Կատալոգ') ?></h1>
            <p class="text-muted">Նոութբուքեր, մոնիտորներ, սմարթֆոններ և աքսեսուարներ՝ 2 տարվա երաշխիքով</p>
        </div>
    </div>
</section>

<section id="catalog">
    <div class="row align-start flex-wrap">

        <aside class="col w-25 w-md-100" aria-label="Ֆիլտրեր">
            <form class="filters" action="/catalog" method="get">
                <h2 class="title-4 mb-20">Ֆիլտրեր</h2>
                <?php if ($sort !== 'newest'): ?>
                    <input type="hidden" name="sort" value="<?= e($sort) ?>">
                <?php endif; ?>

                <div class="filters__groups">
                    <fieldset class="filters__group">
                        <legend>Կատեգորիա</legend>
                        <ul>
                            <li><label class="check"><input type="radio" name="category" value=""<?= $category === null ? ' checked' : '' ?>> Բոլորը</label></li>
                            <?php foreach ($categories as $c): ?>
                                <li><label class="check"><input type="radio" name="category" value="<?= e($c->slug) ?>"<?= $category?->id === $c->id ? ' checked' : '' ?>> <?= e($c->name) ?></label></li>
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
                            <?php foreach (ProductRepository::STOCK as $value => $label): ?>
                                <li><label class="check"><input type="radio" name="stock" value="<?= e($value) ?>"<?= $filters['stock'] === $value ? ' checked' : '' ?>> <?= e($label) ?></label></li>
                            <?php endforeach; ?>
                        </ul>
                    </fieldset>
                </div>

                <input type="hidden" name="q" value="<?= e($filters['q']) ?>">
                <div class="flex gap-10 flex-wrap">
                    <button type="submit" class="btn btn--primary">Կիրառել</button>
                    <a href="/catalog" class="btn btn--ghost">Մաքրել</a>
                </div>
            </form>
        </aside>

        <div class="col w-75 w-md-100">
            <div class="toolbar flex justify-between align-center flex-wrap gap-10">
                <p class="text-muted" role="status" data-search-status>
                    <?= $total > 0 ? "Ցուցադրված է {$from}–{$to}՝ {$total} ապրանքից" : 'Ոչինչ չգտնվեց' ?>
                </p>

                <!-- data-live-search՝ JS-ը արդյունքները կթարմացնի առանց վերբեռնման (PHP Դաս 17-ի խորացված տնային) -->
                <form class="flex align-center flex-wrap gap-10" action="<?= e($path === '/search' ? '/catalog' : $path) ?>" method="get" role="search" data-live-search<?= $category && $path !== '/catalog' ? ' data-category="' . e($category->slug) . '"' : '' ?>>
                    <?php foreach (['category', 'min', 'max', 'stock'] as $key): ?>
                        <?php if ($query[$key] !== null && $query[$key] !== 'all'): ?>
                            <input type="hidden" name="<?= $key ?>" value="<?= e((string) $query[$key]) ?>">
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <label for="q" class="visually-hidden">Որոնել</label>
                    <input id="q" name="q" type="search" class="input input--sm toolbar__search" placeholder="Որոնել…" value="<?= e($filters['q']) ?>" maxlength="100" autocomplete="off">

                    <label for="sort" class="field__label">Դասավորել</label>
                    <select id="sort" name="sort" class="input input--sm">
                        <?php foreach (ProductRepository::SORTS as $key => [$label]): ?>
                            <option value="<?= e($key) ?>"<?= $sort === $key ? ' selected' : '' ?>><?= e($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn btn--ghost btn--sm">Կիրառել</button>
                </form>
            </div>

            <div data-search-results>
                <?php if ($products === []): ?>
                    <div class="empty-state">
                        <p><?= $filters['q'] !== '' ? '«' . e($filters['q']) . '» հարցմամբ' : 'Այս ֆիլտրերով' ?> ապրանք չկա։</p>
                        <a href="/catalog" class="btn btn--primary">Մաքրել ֆիլտրերը</a>
                    </div>
                <?php else: ?>
                    <div class="grid-auto gap-20">
                        <?php foreach ($products as $p): ?>
                            <?= render('partials/card', ['p' => $p, 'back' => $back]) ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?= render('partials/pagination', ['page' => $page, 'pages' => $pages, 'query' => $query, 'path' => $path]) ?>
            </div>
        </div>
    </div>
</section>
