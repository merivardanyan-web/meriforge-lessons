<?php /** @var array $items @var array $summary @var array $errors @var array $old */ ?>
<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <nav aria-label="Դուք այստեղ եք">
                <ol class="crumbs">
                    <li><a href="/">Գլխավոր</a></li>
                    <li><a href="/cart.php">Զամբյուղ</a></li>
                    <li aria-current="page">Պատվերի ձևակերպում</li>
                </ol>
            </nav>
            <h1 class="mb-10">Պատվերի ձևակերպում</h1>
            <p class="text-muted">Մնաց մեկ քայլ։ <span class="req" aria-hidden="true">*</span> նշված դաշտերը պարտադիր են։</p>
        </div>
    </div>
</section>

<section id="checkout">
    <div class="row flex-wrap">
        <div class="col w-60 w-md-100">

            <?php if ($errors !== []): ?>
                <div class="alert alert--danger" role="alert">
                    <?= isset($errors['_form']) ? e($errors['_form']) : 'Ուղղեք ' . count($errors) . ' սխալ և փորձեք կրկին։' ?>
                </div>
            <?php endif; ?>

            <!-- novalidate․ ստուգում է սերվերը, ոչ բրաուզերը — PHP Դաս 6 -->
            <form action="/checkout.php" method="post" novalidate>

                <div class="form-section">
                    <fieldset class="fieldset">
                        <legend>1. Կոնտակտային տվյալներ</legend>
                        <div class="form-grid">
                            <div class="field">
                                <label for="name" class="field__label">Անուն, ազգանուն <span class="req" aria-hidden="true">*</span></label>
                                <input id="name" name="name" class="<?= inputClass($errors, 'name') ?>" autocomplete="name" maxlength="100" required
                                       value="<?= e($old['name'] ?? '') ?>" <?= ariaError($errors, 'name') ?>>
                                <?= fieldError($errors, 'name') ?>
                            </div>
                            <div class="field">
                                <label for="phone" class="field__label">Հեռախոս <span class="req" aria-hidden="true">*</span></label>
                                <input id="phone" name="phone" type="tel" class="<?= inputClass($errors, 'phone') ?>" autocomplete="tel" placeholder="+374 XX XX XX XX" required
                                       value="<?= e($old['phone'] ?? '') ?>" <?= ariaError($errors, 'phone') ?>>
                                <?= fieldError($errors, 'phone') ?>
                            </div>
                            <div class="field field--full">
                                <label for="email" class="field__label">Էլ. հասցե</label>
                                <input id="email" name="email" type="email" class="<?= inputClass($errors, 'email') ?>" autocomplete="email" placeholder="ani@example.com"
                                       value="<?= e($old['email'] ?? '') ?>" <?= ariaError($errors, 'email') ?>>
                                <?= fieldError($errors, 'email') ?>
                                <span class="field__hint">Կուղարկենք պատվերի հաստատումը</span>
                            </div>
                        </div>
                    </fieldset>
                </div>

                <div class="form-section">
                    <fieldset class="fieldset">
                        <legend>2. Առաքում</legend>
                        <div class="choices">
                            <?php foreach (DELIVERY as $value => [$label, $hint]): ?>
                                <label class="choice">
                                    <input type="radio" name="delivery" value="<?= e($value) ?>"<?= ($old['delivery'] ?? '') === $value ? ' checked' : '' ?>>
                                    <span class="choice__body"><strong><?= e($label) ?></strong><span class="text-muted"><?= e($hint) ?></span></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                        <?= fieldError($errors, 'delivery') ?>
                        <div class="form-grid">
                            <div class="field">
                                <label for="region" class="field__label">Մարզ</label>
                                <select id="region" name="region" class="<?= inputClass($errors, 'region') ?>" autocomplete="address-level1" <?= ariaError($errors, 'region') ?>>
                                    <?php foreach (REGIONS as $region): ?>
                                        <option<?= ($old['region'] ?? '') === $region ? ' selected' : '' ?>><?= e($region) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?= fieldError($errors, 'region') ?>
                            </div>
                            <div class="field">
                                <label for="address" class="field__label">Հասցե <span class="req" aria-hidden="true">*</span></label>
                                <input id="address" name="address" class="<?= inputClass($errors, 'address') ?>" autocomplete="street-address" placeholder="Փողոց, շենք, բնակարան" maxlength="255"
                                       value="<?= e($old['address'] ?? '') ?>" <?= ariaError($errors, 'address') ?>>
                                <?= fieldError($errors, 'address') ?>
                                <span class="field__hint">Խանութից վերցնելիս պետք չէ</span>
                            </div>
                            <div class="field field--full">
                                <label for="comment" class="field__label">Մեկնաբանություն առաքիչին</label>
                                <textarea id="comment" name="comment" rows="3" maxlength="500" class="<?= inputClass($errors, 'comment') ?>" placeholder="Օրինակ՝ զանգել մուտքի մոտից" <?= ariaError($errors, 'comment') ?>><?= e($old['comment'] ?? '') ?></textarea>
                                <?= fieldError($errors, 'comment') ?>
                            </div>
                        </div>
                    </fieldset>
                </div>

                <div class="form-section">
                    <fieldset class="fieldset">
                        <legend>3. Վճարում</legend>
                        <div class="choices">
                            <?php foreach (PAYMENT as $value => [$label, $hint]): ?>
                                <label class="choice">
                                    <input type="radio" name="payment" value="<?= e($value) ?>"<?= ($old['payment'] ?? '') === $value ? ' checked' : '' ?>>
                                    <span class="choice__body"><strong><?= e($label) ?></strong><span class="text-muted"><?= e($hint) ?></span></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                        <?= fieldError($errors, 'payment') ?>
                    </fieldset>
                </div>

                <div class="form-section">
                    <label class="check<?= isset($errors['agree']) ? ' has-error' : '' ?>">
                        <input type="checkbox" name="agree" value="1"<?= isset($old['agree']) ? ' checked' : '' ?> <?= ariaError($errors, 'agree') ?>>
                        <span>Համաձայն եմ առաքման և վերադարձի պայմաններին</span>
                    </label>
                    <?= fieldError($errors, 'agree') ?>
                </div>

                <button type="submit" class="btn btn--primary">Հաստատել պատվերը՝ <?= formatPrice($summary['total']) ?></button>
            </form>
        </div>

        <div class="col w-40 w-md-100">
            <aside class="summary" aria-labelledby="order-title">
                <h2 id="order-title" class="title-4 mb-20">Ձեր պատվերը</h2>
                <ul class="summary__items mb-20">
                    <?php foreach ($items as $item): ?>
                        <li>
                            <picture class="ratio-1-1"><img class="img-cover" src="/images/<?= e($item['product']['image'] ?? 'placeholder.jpg') ?>" alt="" width="600" height="400" loading="lazy"></picture>
                            <span><?= e($item['product']['name']) ?> × <?= $item['qty'] ?></span>
                            <strong><?= formatPrice($item['sum']) ?></strong>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <?= render('partials/summary', ['summary' => $summary]) ?>
            </aside>
        </div>
    </div>
</section>
