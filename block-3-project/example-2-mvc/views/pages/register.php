<?php /** @var array $errors  @var array $old */ ?>
<section class="auth">
    <div class="row">
        <div class="col w-100">
            <div class="auth__card">
                <nav aria-label="Դուք այստեղ եք">
                    <ol class="crumbs">
                        <li><a href="/">Գլխավոր</a></li>
                        <li aria-current="page">Գրանցում</li>
                    </ol>
                </nav>
                <h1>Գրանցում</h1>
                <p class="text-muted mb-20">Հաշիվը պետք է պատվերների պատմության համար։ Գնել կարելի է նաև առանց դրա։</p>

                <?php if ($errors !== []): ?>
                    <div class="alert alert--danger" role="alert">Ուղղեք <?= count($errors) ?> սխալ և փորձեք կրկին։</div>
                <?php endif; ?>

                <form method="post" action="/register" novalidate>
                    <?= csrf_field() ?>

                    <div class="field">
                        <label for="name" class="field__label">Անուն</label>
                        <input id="name" name="name" class="<?= inputClass($errors, 'name') ?>" autocomplete="name" maxlength="100" required
                               value="<?= e($old['name'] ?? '') ?>" <?= ariaError($errors, 'name') ?>>
                        <?= fieldError($errors, 'name') ?>
                    </div>

                    <div class="field">
                        <label for="email" class="field__label">Էլ. հասցե</label>
                        <input id="email" name="email" type="email" class="<?= inputClass($errors, 'email') ?>" autocomplete="email" required
                               value="<?= e($old['email'] ?? '') ?>" <?= ariaError($errors, 'email') ?>>
                        <?= fieldError($errors, 'email') ?>
                    </div>

                    <div class="field">
                        <label for="password" class="field__label">Գաղտնաբառ (առնվազն 8 նիշ)</label>
                        <input id="password" name="password" type="password" class="<?= inputClass($errors, 'password') ?>"
                               autocomplete="new-password" minlength="8" required <?= ariaError($errors, 'password') ?>>
                        <?= fieldError($errors, 'password') ?>
                    </div>

                    <div class="field">
                        <label for="password_confirmation" class="field__label">Կրկնիր գաղտնաբառը</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" class="<?= inputClass($errors, 'password_confirmation') ?>"
                               autocomplete="new-password" required <?= ariaError($errors, 'password_confirmation') ?>>
                        <?= fieldError($errors, 'password_confirmation') ?>
                    </div>

                    <button type="submit" class="btn btn--primary w-100">Ստեղծել հաշիվ</button>
                </form>
                <p class="auth__switch">Արդեն ունե՞ս հաշիվ՝ <a href="/login" class="link">Մուտք</a></p>
            </div>
        </div>
    </div>
</section>
