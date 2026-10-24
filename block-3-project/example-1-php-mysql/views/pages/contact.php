<?php /** @var array $errors @var array $old @var array $topics */ ?>
<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <nav aria-label="Դուք այստեղ եք">
                <ol class="crumbs">
                    <li><a href="/">Գլխավոր</a></li>
                    <li aria-current="page">Կապ</li>
                </ol>
            </nav>
            <h1 class="mb-10">Կապ մեզ հետ</h1>
            <p class="text-muted">Գրեք <?= e(SITE_NAME) ?>-ի թիմին։ Կպատասխանենք մեկ աշխատանքային օրվա ընթացքում։</p>
        </div>
    </div>
</section>

<section id="contact-form">
    <div class="row flex-wrap">
        <div class="col w-60 w-md-100">

            <?php if ($errors !== []): ?>
                <div class="alert alert--danger" role="alert">Ուղղեք <?= count($errors) ?> սխալ և փորձեք կրկին։</div>
            <?php endif; ?>

            <form method="post" action="/contact.php" class="form-section" novalidate>
                <div class="form-grid">
                    <div class="field">
                        <label for="name" class="field__label">Անուն <span class="req" aria-hidden="true">*</span></label>
                        <input type="text" id="name" name="name" maxlength="60" autocomplete="name"
                               class="<?= inputClass($errors, 'name') ?>" value="<?= e($old['name'] ?? '') ?>" <?= ariaError($errors, 'name') ?>>
                        <?= fieldError($errors, 'name') ?>
                    </div>

                    <div class="field">
                        <label for="email" class="field__label">Էլ. հասցե <span class="req" aria-hidden="true">*</span></label>
                        <input type="email" id="email" name="email" autocomplete="email"
                               class="<?= inputClass($errors, 'email') ?>" value="<?= e($old['email'] ?? '') ?>" <?= ariaError($errors, 'email') ?>>
                        <?= fieldError($errors, 'email') ?>
                    </div>

                    <div class="field field--full">
                        <label for="topic" class="field__label">Թեմա <span class="req" aria-hidden="true">*</span></label>
                        <select id="topic" name="topic" class="<?= inputClass($errors, 'topic') ?>" <?= ariaError($errors, 'topic') ?>>
                            <option value="">— ընտրեք —</option>
                            <?php foreach ($topics as $key => $label): ?>
                                <option value="<?= e($key) ?>"<?= ($old['topic'] ?? '') === $key ? ' selected' : '' ?>><?= e($label) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?= fieldError($errors, 'topic') ?>
                    </div>

                    <div class="field field--full">
                        <label for="message" class="field__label">Հաղորդագրություն <span class="req" aria-hidden="true">*</span></label>
                        <textarea id="message" name="message" rows="6" maxlength="2000"
                                  class="<?= inputClass($errors, 'message') ?>" <?= ariaError($errors, 'message') ?>><?= e($old['message'] ?? '') ?></textarea>
                        <?= fieldError($errors, 'message') ?>
                    </div>
                </div>

                <button type="submit" class="btn btn--primary mt-20">Ուղարկել</button>
            </form>
        </div>

        <div class="col w-40 w-md-100">
            <div class="form-section">
                <h2 class="title-4 mb-10">Խանութ</h2>
                <p>Երևան, Աբովյան 1<br>Ամեն օր՝ 10:00–20:00</p>
                <p class="mt-10"><a href="tel:+37410000000" class="link">+374 10 00 00 00</a></p>
            </div>
        </div>
    </div>
</section>
