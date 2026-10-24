<?php
$has  = fn(string $key): string => isset($errors[$key]) ? ' has-error' : '';
$aria = fn(string $key): string => isset($errors[$key]) ? ' aria-invalid="true" aria-describedby="' . $key . '-error"' : '';
$msg  = fn(string $key): string => '<small class="field__error" id="' . $key . '-error">' . e($errors[$key] ?? '') . '</small>';
?>
<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <div class="wrapper">
                <h1>Միանալ ակումբին</h1>
                <p class="lead">Ամեն ամիս՝ մեկ գիրք և մեկ հանդիպում։ Արդեն անդա՞մ ես՝ <a href="/login">մուտք</a>։</p>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="row">
        <div class="col w-60 w-md-100">
            <?php if ($errors !== []): ?>
                <div class="alert alert--danger" role="alert">Ուղղիր <?= count($errors) ?> սխալ և փորձիր կրկին։</div>
            <?php endif; ?>

            <form method="post" action="/register" novalidate>
                <?= csrf_field() ?>
                <div class="form-grid">
                    <label for="name">Անուն</label>
                    <div class="field<?= $has('name') ?>">
                        <input type="text" id="name" name="name" maxlength="60" autocomplete="name" value="<?= e($old['name'] ?? '') ?>"<?= $aria('name') ?>>
                        <?= $msg('name') ?>
                    </div>

                    <label for="email">Էլ. հասցե</label>
                    <div class="field<?= $has('email') ?>">
                        <input type="email" id="email" name="email" autocomplete="email" value="<?= e($old['email'] ?? '') ?>"<?= $aria('email') ?>>
                        <?= $msg('email') ?>
                    </div>

                    <label for="password">Գաղտնաբառ</label>
                    <div class="field<?= $has('password') ?>">
                        <input type="password" id="password" name="password" autocomplete="new-password" minlength="8"<?= $aria('password') ?>>
                        <small class="meta">Առնվազն 8 նիշ։ Երկար արտահայտությունը ավելի ապահով է, քան «P@ss1»-ը</small>
                        <?= $msg('password') ?>
                    </div>

                    <label for="password_confirmation">Կրկնիր</label>
                    <div class="field<?= $has('password_confirmation') ?>">
                        <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password"<?= $aria('password_confirmation') ?>>
                        <?= $msg('password_confirmation') ?>
                    </div>
                </div>
                <button type="submit" class="btn btn--primary mt-20">Միանալ</button>
            </form>
        </div>
    </div>
</section>
