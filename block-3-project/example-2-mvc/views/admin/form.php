<?php /** @var array $errors @var array $old @var ?int $id @var App\Models\Category[] $categories */
$isEdit = $id !== null;
?>
<section>
    <div class="row">
        <div class="col w-60 w-md-100">
            <h1 class="mb-20 mt-20"><?= $isEdit ? 'Խմբագրում՝ ' . e($old['name'] ?? '') : 'Նոր ապրանք' ?></h1>

            <?php if ($errors !== []): ?>
                <div class="alert alert--danger" role="alert">Ուղղեք <?= count($errors) ?> սխալ և փորձեք կրկին։</div>
            <?php endif; ?>

            <!-- enctype՝ առանց սրա $_FILES-ը դատարկ է — PHP Դաս 7 -->
            <form method="post" enctype="multipart/form-data" novalidate class="form-section"
                  action="<?= $isEdit ? '/admin/products/' . $id : '/admin/products' ?>">
                <?= csrf_field() ?>

                <div class="form-grid">
                    <div class="field field--full">
                        <label for="name" class="field__label">Անվանում <span class="req" aria-hidden="true">*</span></label>
                        <input id="name" name="name" maxlength="200" class="<?= inputClass($errors, 'name') ?>"
                               value="<?= e((string) ($old['name'] ?? '')) ?>" <?= ariaError($errors, 'name') ?>>
                        <?= fieldError($errors, 'name') ?>
                    </div>

                    <div class="field">
                        <label for="category_id" class="field__label">Կատեգորիա</label>
                        <select id="category_id" name="category_id" class="<?= inputClass($errors, 'category_id') ?>" <?= ariaError($errors, 'category_id') ?>>
                            <option value="">— առանց կատեգորիայի —</option>
                            <?php foreach ($categories as $c): ?>
                                <option value="<?= $c->id ?>"<?= (int) ($old['category_id'] ?? 0) === $c->id ? ' selected' : '' ?>><?= e($c->name) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?= fieldError($errors, 'category_id') ?>
                    </div>

                    <div class="field">
                        <label for="specs" class="field__label">Համառոտ (քարտի վրա)</label>
                        <input id="specs" name="specs" maxlength="255" class="<?= inputClass($errors, 'specs') ?>"
                               value="<?= e((string) ($old['specs'] ?? '')) ?>" <?= ariaError($errors, 'specs') ?>>
                        <?= fieldError($errors, 'specs') ?>
                    </div>

                    <div class="field">
                        <label for="price" class="field__label">Գին, ֏ <span class="req" aria-hidden="true">*</span></label>
                        <input id="price" name="price" type="number" min="1" class="<?= inputClass($errors, 'price') ?>"
                               value="<?= e((string) ($old['price'] ?? '')) ?>" <?= ariaError($errors, 'price') ?>>
                        <?= fieldError($errors, 'price') ?>
                    </div>

                    <div class="field">
                        <label for="discount" class="field__label">Զեղչ, %</label>
                        <input id="discount" name="discount" type="number" min="0" max="90" class="<?= inputClass($errors, 'discount') ?>"
                               value="<?= e((string) ($old['discount'] ?? '0')) ?>" <?= ariaError($errors, 'discount') ?>>
                        <?= fieldError($errors, 'discount') ?>
                    </div>

                    <div class="field">
                        <label for="stock" class="field__label">Պահեստում, հատ</label>
                        <input id="stock" name="stock" type="number" min="0" class="<?= inputClass($errors, 'stock') ?>"
                               value="<?= e((string) ($old['stock'] ?? '0')) ?>" <?= ariaError($errors, 'stock') ?>>
                        <?= fieldError($errors, 'stock') ?>
                    </div>

                    <div class="field">
                        <label for="type" class="field__label">Տեսակ</label>
                        <select id="type" name="type" class="<?= inputClass($errors, 'type') ?>" <?= ariaError($errors, 'type') ?>>
                            <option value="physical"<?= ($old['type'] ?? '') === 'physical' ? ' selected' : '' ?>>Ֆիզիկական</option>
                            <option value="digital"<?= ($old['type'] ?? '') === 'digital' ? ' selected' : '' ?>>Թվային (լիցենզիա)</option>
                        </select>
                        <?= fieldError($errors, 'type') ?>
                    </div>

                    <div class="field">
                        <label for="weight" class="field__label">Քաշ, գրամ</label>
                        <input id="weight" name="weight" type="number" min="0" class="<?= inputClass($errors, 'weight') ?>"
                               value="<?= e((string) ($old['weight'] ?? '0')) ?>" <?= ariaError($errors, 'weight') ?>>
                        <?= fieldError($errors, 'weight') ?>
                    </div>

                    <div class="field">
                        <label for="image" class="field__label">Նկար (JPG, PNG, WebP, մինչև 2 ՄԲ)</label>
                        <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp" class="<?= inputClass($errors, 'image') ?>" <?= ariaError($errors, 'image') ?>>
                        <?= fieldError($errors, 'image') ?>
                        <?php if (!empty($old['image'])): ?>
                            <span class="field__hint">Ընթացիկը՝ <?= e($old['image']) ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="field field--full">
                        <label for="description" class="field__label">Նկարագրություն</label>
                        <textarea id="description" name="description" rows="5" maxlength="5000" class="<?= inputClass($errors, 'description') ?>" <?= ariaError($errors, 'description') ?>><?= e((string) ($old['description'] ?? '')) ?></textarea>
                        <?= fieldError($errors, 'description') ?>
                    </div>

                    <div class="field field--full">
                        <label class="check">
                            <input type="checkbox" name="is_active" value="1"<?= !empty($old['is_active']) ? ' checked' : '' ?>>
                            <span>Ակտիվ (երևում է կայքում)</span>
                        </label>
                    </div>
                </div>

                <div class="flex gap-10 flex-wrap mt-20">
                    <button type="submit" class="btn btn--primary"><?= $isEdit ? 'Պահպանել' : 'Ավելացնել' ?></button>
                    <a href="/admin" class="btn btn--ghost">Չեղարկել</a>
                </div>
            </form>
        </div>
    </div>
</section>
