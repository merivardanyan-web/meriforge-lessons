<?php
// Գրքի ֆորմա — PHP Դաս 11․ նույն ֆայլը՝ ստեղծման և խմբագրման համար
// Երկու փոքր օգնական՝ որ ամեն դաշտում չկրկնենք նույն երեք տողը
$has = fn(string $key): string => isset($errors[$key]) ? ' has-error' : '';
$aria = fn(string $key): string => isset($errors[$key]) ? ' aria-invalid="true" aria-describedby="' . $key . '-error"' : '';
$msg = fn(string $key): string => '<small class="field__error" id="' . $key . '-error">' . e($errors[$key] ?? '') . '</small>';
$val = fn(string $key): string => e((string) ($old[$key] ?? ''));
?>
<section>
    <div class="row">
        <div class="col w-70 w-md-100">
            <h1 class="mt-20"><?= $isEdit ? 'Խմբագրում՝ ' . $val('title') : 'Նոր գիրք' ?></h1>

            <?php if ($errors !== []): ?>
                <div class="alert alert--danger" role="alert">Ուղղիր <?= count($errors) ?> սխալ և փորձիր կրկին։</div>
            <?php endif; ?>

            <!-- enctype՝ առանց սրա $_FILES-ը դատարկ է (Դաս 07) -->
            <form method="post" enctype="multipart/form-data" novalidate action="form.php<?= $isEdit ? '?id=' . $id : '' ?>">
                <div class="form-grid">
                    <label for="title">Վերնագիր *</label>
                    <div class="field<?= $has('title') ?>">
                        <input type="text" id="title" name="title" maxlength="200" value="<?= $val('title') ?>"<?= $aria('title') ?>>
                        <?= $msg('title') ?>
                    </div>

                    <label for="author">Հեղինակ *</label>
                    <div class="field<?= $has('author') ?>">
                        <input type="text" id="author" name="author" maxlength="100" value="<?= $val('author') ?>"<?= $aria('author') ?>>
                        <?= $msg('author') ?>
                    </div>

                    <label for="genre_id">Ժանր</label>
                    <div class="field<?= $has('genre_id') ?>">
                        <select id="genre_id" name="genre_id"<?= $aria('genre_id') ?>>
                            <option value="">— առանց ժանրի —</option>
                            <?php foreach ($genres as $g): ?>
                                <option value="<?= $g->id ?>"<?= (string) $old['genre_id'] === (string) $g->id ? ' selected' : '' ?>><?= e($g->name) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?= $msg('genre_id') ?>
                    </div>

                    <label for="year">Տարի</label>
                    <div class="field<?= $has('year') ?>">
                        <input type="number" id="year" name="year" min="1" max="<?= date('Y') ?>" value="<?= $val('year') ?>"<?= $aria('year') ?>>
                        <?= $msg('year') ?>
                    </div>

                    <label for="pages">Էջեր *</label>
                    <div class="field<?= $has('pages') ?>">
                        <input type="number" id="pages" name="pages" min="1" max="5000" value="<?= $val('pages') ?>"<?= $aria('pages') ?>>
                        <?= $msg('pages') ?>
                    </div>

                    <label for="read_pages">Կարդացած</label>
                    <div class="field<?= $has('read_pages') ?>">
                        <input type="number" id="read_pages" name="read_pages" min="0" value="<?= $val('read_pages') ?>"<?= $aria('read_pages') ?>>
                        <?= $msg('read_pages') ?>
                    </div>

                    <label for="rating">Գնահատական</label>
                    <div class="field<?= $has('rating') ?>">
                        <select id="rating" name="rating"<?= $aria('rating') ?>>
                            <?php for ($i = 0; $i <= 5; $i++): ?>
                                <option value="<?= $i ?>"<?= (int) ($old['rating'] ?? 0) === $i ? ' selected' : '' ?>><?= $i === 0 ? 'Դեռ չեմ գնահատել' : $i . ' ★' ?></option>
                            <?php endfor; ?>
                        </select>
                        <?= $msg('rating') ?>
                    </div>

                    <label for="color">Շապիկի գույն</label>
                    <div class="field<?= $has('color') ?>">
                        <input type="color" id="color" name="color" value="<?= $val('color') ?>"<?= $aria('color') ?>>
                        <?= $msg('color') ?>
                    </div>

                    <label for="link">Հղում</label>
                    <div class="field<?= $has('link') ?>">
                        <input type="url" id="link" name="link" maxlength="255" placeholder="https://" value="<?= $val('link') ?>"<?= $aria('link') ?>>
                        <?= $msg('link') ?>
                    </div>

                    <label for="cover">Շապիկի նկար</label>
                    <div class="field<?= $has('cover') ?>">
                        <input type="file" id="cover" name="cover" accept="image/jpeg,image/png,image/webp"<?= $aria('cover') ?>>
                        <?= $msg('cover') ?>
                        <?php if (!empty($old['cover'])): ?><small class="meta">Ընթացիկը՝ <?= $val('cover') ?></small><?php endif; ?>
                    </div>

                    <label class="check">
                        <input type="checkbox" name="is_active" value="1"<?= !empty($old['is_active']) ? ' checked' : '' ?>>
                        Երևում է կայքում
                    </label>
                </div>

                <div class="flex gap-10 flex-wrap mt-20">
                    <button type="submit" class="btn btn--primary"><?= $isEdit ? 'Պահպանել' : 'Ավելացնել' ?></button>
                    <a href="index.php" class="btn btn--ghost">Չեղարկել</a>
                </div>
            </form>
        </div>
    </div>
</section>
