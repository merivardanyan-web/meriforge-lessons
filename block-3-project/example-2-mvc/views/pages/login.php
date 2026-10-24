<?php /** @var ?string $error  @var string $email */ ?>
<section class="auth">
    <div class="row">
        <div class="col w-100">
            <div class="auth__card">
                <nav aria-label="Դուք այստեղ եք">
                    <ol class="crumbs">
                        <li><a href="/">Գլխավոր</a></li>
                        <li aria-current="page">Մուտք</li>
                    </ol>
                </nav>
                <h1>Մուտք</h1>
                <p class="text-muted mb-20">Մուտք գործիր՝ պատվերներդ տեսնելու համար։</p>

                <?php if ($error): ?>
                    <div class="alert alert--danger" role="alert"><?= e($error) ?></div>
                <?php endif; ?>

                <!-- Նույն name-երը, ինչ Բլոկ 1-ի login.html-ում — հիմա ֆորման իսկապես աշխատում է -->
                <form action="/login" method="post" novalidate>
                    <?= csrf_field() ?>
                    <div class="field">
                        <label for="email" class="field__label">Էլ. հասցե <span class="req" aria-hidden="true">*</span></label>
                        <input id="email" name="email" type="email" class="input" autocomplete="username" required
                               value="<?= e($email) ?>" placeholder="ani@example.com">
                    </div>
                    <div class="field">
                        <label for="password" class="field__label">Գաղտնաբառ <span class="req" aria-hidden="true">*</span></label>
                        <!-- value չկա՝ գաղտնաբառը երբեք չի վերադառնում -->
                        <input id="password" name="password" type="password" class="input" autocomplete="current-password" required>
                    </div>
                    <button type="submit" class="btn btn--primary w-100">Մուտք գործել</button>
                </form>
                <p class="auth__switch">Դեռ հաշիվ չունե՞ս՝ <a href="/register" class="link">Գրանցվիր</a></p>
            </div>
        </div>
    </div>
</section>
