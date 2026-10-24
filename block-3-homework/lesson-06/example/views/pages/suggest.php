<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <div class="wrapper">
                <h1>Առաջարկիր գիրք</h1>
                <p class="lead">Ի՞նչ արժե կարդալ ակումբում հաջորդ ամիս։ <span aria-hidden="true">*</span> նշվածները պարտադիր են։</p>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="row">
        <div class="col w-70 w-md-100">
            <div class="wrapper">

                <?php if ($success): ?>
                    <div class="alert alert--success" role="status">Շնորհակալություն։ Անին կկարդա առաջարկդ այս շաբաթ։</div>
                <?php endif; ?>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert--danger" role="alert">Ուղղիր <?= count($errors) ?> սխալ և փորձիր կրկին։</div>
                <?php endif; ?>

                <!-- novalidate․ ստուգում է սերվերը (Դաս 06) -->
                <form method="post" action="suggest.php" novalidate>
                    <fieldset>
                        <legend>Դու</legend>
                        <div class="form-grid">
                            <label for="name">Անուն *</label>
                            <div class="field<?= isset($errors['name']) ? ' has-error' : '' ?>">
                                <input type="text" id="name" name="name" maxlength="60" autocomplete="name" value="<?= e($old['name'] ?? '') ?>"
                                    <?php if (isset($errors['name'])): ?>aria-invalid="true" aria-describedby="name-error"<?php endif; ?>>
                                <small class="field__error" id="name-error"><?= e($errors['name'] ?? '') ?></small>
                            </div>

                            <label for="email">Էլ. հասցե *</label>
                            <div class="field<?= isset($errors['email']) ? ' has-error' : '' ?>">
                                <input type="email" id="email" name="email" autocomplete="email" value="<?= e($old['email'] ?? '') ?>"
                                    <?php if (isset($errors['email'])): ?>aria-invalid="true" aria-describedby="email-error"<?php endif; ?>>
                                <small class="field__error" id="email-error"><?= e($errors['email'] ?? '') ?></small>
                            </div>

                            <label for="phone">Հեռախոս</label>
                            <div class="field<?= isset($errors['phone']) ? ' has-error' : '' ?>">
                                <input type="tel" id="phone" name="phone" placeholder="+374 91 000000" autocomplete="tel" value="<?= e($old['phone'] ?? '') ?>"
                                    <?php if (isset($errors['phone'])): ?>aria-invalid="true" aria-describedby="phone-error"<?php endif; ?>>
                                <small class="field__error" id="phone-error"><?= e($errors['phone'] ?? '') ?></small>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend>Գիրքը</legend>
                        <div class="form-grid">
                            <label for="title">Վերնագիր *</label>
                            <div class="field<?= isset($errors['title']) ? ' has-error' : '' ?>">
                                <input type="text" id="title" name="title" maxlength="120" value="<?= e($old['title'] ?? '') ?>"
                                    <?php if (isset($errors['title'])): ?>aria-invalid="true" aria-describedby="title-error"<?php endif; ?>>
                                <small class="field__error" id="title-error"><?= e($errors['title'] ?? '') ?></small>
                            </div>

                            <label for="author">Հեղինակ</label>
                            <div class="field<?= isset($errors['author']) ? ' has-error' : '' ?>">
                                <input type="text" id="author" name="author" maxlength="80" value="<?= e($old['author'] ?? '') ?>">
                                <small class="field__error"><?= e($errors['author'] ?? '') ?></small>
                            </div>

                            <label for="genre">Ժանր *</label>
                            <div class="field<?= isset($errors['genre']) ? ' has-error' : '' ?>">
                                <select id="genre" name="genre"
                                    <?php if (isset($errors['genre'])): ?>aria-invalid="true" aria-describedby="genre-error"<?php endif; ?>>
                                    <option value="">— ընտրիր —</option>
                                    <?php foreach ($genres as $genre): ?>
                                        <option value="<?= e($genre) ?>"<?= ($old['genre'] ?? '') === $genre ? ' selected' : '' ?>><?= e($genre) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="field__error" id="genre-error"><?= e($errors['genre'] ?? '') ?></small>
                            </div>

                            <label for="reason">Ինչու *</label>
                            <div class="field<?= isset($errors['reason']) ? ' has-error' : '' ?>">
                                <textarea id="reason" name="reason" rows="5" maxlength="1000"
                                    <?php if (isset($errors['reason'])): ?>aria-invalid="true" aria-describedby="reason-error"<?php endif; ?>><?= e($old['reason'] ?? '') ?></textarea>
                                <small class="field__error" id="reason-error"><?= e($errors['reason'] ?? '') ?></small>
                            </div>

                            <div class="check field<?= isset($errors['agree']) ? ' has-error' : '' ?>">
                                <label><input type="checkbox" name="agree" value="1"<?= isset($old['agree']) ? ' checked' : '' ?>> Համաձայն եմ, որ Անին գրի ինձ</label>
                                <small class="field__error"><?= e($errors['agree'] ?? '') ?></small>
                            </div>
                        </div>
                    </fieldset>

                    <button type="submit" class="btn btn--primary">Ուղարկել առաջարկը</button>
                </form>
            </div>
        </div>
    </div>
</section>
