<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <div class="wrapper">
                <h1>Մուտք</h1>
                <p class="lead">Ակումբի անդամ չե՞ս՝ <a href="/register">միացիր</a>։</p>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="row">
        <div class="col w-50 w-md-100">
            <?php if ($error): ?>
                <div class="alert alert--danger" role="alert"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="post" action="/login" novalidate>
                <?= csrf_field() ?>
                <div class="form-grid">
                    <label for="email">Էլ. հասցե</label>
                    <div class="field">
                        <input type="email" id="email" name="email" autocomplete="username" value="<?= e($email) ?>" required>
                    </div>

                    <label for="password">Գաղտնաբառ</label>
                    <div class="field">
                        <!-- value չկա․ գաղտնաբառը երբեք չի վերադառնում էջ -->
                        <input type="password" id="password" name="password" autocomplete="current-password" required>
                    </div>
                </div>
                <button type="submit" class="btn btn--primary mt-20">Մուտք</button>
            </form>
        </div>
    </div>
</section>
