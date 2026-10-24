<?php $status = $book->status(); $cover = $book->cover; ?>
<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <div class="wrapper">
                <ol class="crumbs">
                    <li><a href="/">Գրքեր</a></li>
                    <li aria-current="page"><?= e($book->title) ?></li>
                </ol>
                <h1><?= e($book->title) ?></h1>
                <p class="lead"><?= e($book->author) ?> · <?= e($book->yearLabel()) ?> · <?= e($book->genre) ?></p>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="row flex-wrap">
        <div class="col w-40 w-md-100">
            <div class="book book--detail is-<?= e($status) ?>">
                <?php if ($cover !== null): ?>
                    <div class="book__cover book__cover--img"><img src="/images/uploads/<?= e($cover) ?>" alt="«<?= e($book->title) ?>»-ի շապիկը" width="400" height="200"></div>
                <?php else: ?>
                    <div class="book__cover" style="--cover: <?= e($book->color) ?>"><span><?= e($book->title) ?></span></div>
                <?php endif; ?>
            </div>

            <?php if (auth()->isStaff()): ?>
            <!-- enctype՝ առանց սրա $_FILES-ը դատարկ է (Դաս 07) -->
            <form method="post" action="/admin<?= e($book->url()) ?>/cover" enctype="multipart/form-data" class="mt-20">
                <?= csrf_field() ?>
                <label for="cover">Շապիկի նկար (JPG, PNG, WebP, մինչև 2 ՄԲ)</label>
                <input type="file" id="cover" name="cover" accept="image/jpeg,image/png,image/webp">
                <button type="submit" class="btn btn--outline btn--sm mt-10">Վերբեռնել</button>
            </form>
            <?php endif; ?>
        </div>
        <div class="col w-60 w-md-100">
            <div class="wrapper">
                <p class="flex gap-10 flex-wrap align-center">
                    <span class="pill"><?= e($book->statusLabel()) ?></span>
                    <span><?= e($book->formatLabel()) ?></span>
                </p>

                <!-- instanceof․ ինտերֆեյսի մեթոդները՝ միայն այն դասերում, որ իրագործում են այն -->
                <?php if ($book instanceof App\Models\Listenable): ?>
                    <p>Տևողությունը՝ <strong><?= e((new App\Services\DurationFormatter())->format($book->durationMinutes())) ?></strong><?php if ($book->narrator() !== null): ?> · կարդում է <?= e($book->narrator()) ?><?php endif; ?></p>
                <?php endif; ?>
                <?php if ($book instanceof App\Models\Lendable && $book->isLent()): ?>
                    <p class="alert">Գիրքը հիմա <?= e($book->lentTo()) ?>-ի մոտ է։</p>
                <?php endif; ?>
                <div class="progress mt-20"><span class="progress__bar" style="--progress: <?= $book->progress() ?>%"></span></div>
                <p class="meta"><?= $book->read ?> / <?= e(formatPages($book->pages)) ?> · <?= $book->progress() ?>%<?php if ($book->status() !== 'done'): ?> · մնաց <?= e($book->timeLeftLabel()) ?><?php endif; ?></p>

                <?php if ($book->link !== null): ?>
                    <!-- ⭐ e(safeUrl(...))՝ երկուսը միասին։ Միայն e()-ն javascript:-ը չի կանգնեցնի -->
                    <p><a href="<?= e(safeUrl($book->link)) ?>" class="btn btn--outline" rel="noopener">Կարդալ գրքի մասին</a></p>
                <?php endif; ?>

                <?php if (!readingList()->has($book->id) && $status !== 'done'): ?>
                    <form method="post" action="/list/add" data-list-add>
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= $book->id ?>">
                        <input type="hidden" name="back" value="<?= e($book->url()) ?>">
                        <button type="submit" class="btn btn--primary">+ Կարդալու ցուցակ</button>
                    </form>
                <?php endif; ?>

                <?php if ($status !== 'done' && auth()->isStaff()): ?>
                    <!-- Դաս 11․ երկու աղյուսակ մեկ տրանզակցիայով (read.php → logReading) -->
                    <form method="post" action="/admin<?= e($book->url()) ?>/read" class="read-form mt-20">
                        <?= csrf_field() ?>
                        <fieldset>
                            <legend>Կարդացի այսօր</legend>
                            <div class="filter-bar">
                                <div class="field field--short">
                                    <label for="pages">Էջ</label>
                                    <input type="number" id="pages" name="pages" min="1" max="<?= $book->pagesLeft() ?>" required>
                                </div>
                                <div class="field">
                                    <label for="read_on">Օր</label>
                                    <input type="date" id="read_on" name="read_on" value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>" required>
                                </div>
                                <div class="field">
                                    <label for="note">Նշում</label>
                                    <input type="text" id="note" name="note" maxlength="255">
                                </div>
                                <div class="filter-bar__actions"><button type="submit" class="btn btn--primary btn--sm">Պահել</button></div>
                            </div>
                        </fieldset>
                    </form>
                <?php endif; ?>

                <?php if ($sessions !== []): ?>
                    <h2 class="mt-20">Ընթերցանության օրեր</h2>
                    <div class="table-wrap">
                        <table>
                            <caption class="visually-hidden">Երբ և քանի էջ եմ կարդացել</caption>
                            <thead><tr><th scope="col">Օր</th><th scope="col">Էջ</th><th scope="col">Նշում</th></tr></thead>
                            <tbody>
                            <?php foreach ($sessions as $s): ?>
                                <tr><td><?= e($s['read_on']) ?></td><td><?= (int) $s['pages'] ?></td><td><?= e($s['note'] ?? '') ?></td></tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

                <p class="mt-20"><a href="/" class="btn btn--ghost">← Ամբողջ դարակը</a></p>
            </div>
        </div>
    </div>
</section>
