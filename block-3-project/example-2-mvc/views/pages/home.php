<?php /** @var array $products  @var array $sale */ ?>
<section id="hero">
    <div class="row">
        <div class="col w-70 w-md-100">
            <div class="wrapper">
                <h1 class="mb-10">Տեխնիկա, որին կարելի է վստահել</h1>
                <p class="hero__lead mb-20">
                    Ընտրիր 5000+ ապրանքից · Անվճար առաքում Երևանում · 2 տարվա երաշխիք
                </p>
                <div class="flex gap-10 flex-wrap">
                    <a href="/catalog" class="btn btn--light">Դիտել կատալոգը</a>
                    <a href="#features" class="btn btn--outline-light">Մեր մասին</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="products">
    <div class="row">
        <div class="col w-100">
            <h2 class="section-title mb-30">Նոր ապրանքներ</h2>

            <div class="grid-auto gap-20 mb-30">
                <?php foreach ($products as $p): ?>
                    <?= render('partials/card', ['p' => $p, 'back' => '/']) ?>
                <?php endforeach; ?>
            </div>

            <div class="flex justify-center">
                <a href="/catalog" class="btn btn--ghost">Ամբողջ կատալոգը</a>
            </div>
        </div>
    </div>
</section>

<?php if ($sale !== []): ?>
<section id="sale" class="bg-surface">
    <div class="row">
        <div class="col w-100">
            <h2 class="section-title mb-30">Զեղչեր</h2>
            <div class="grid-auto gap-20">
                <?php foreach ($sale as $p): ?>
                    <?= render('partials/card', ['p' => $p, 'back' => '/']) ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<section id="features">
    <div class="row">
        <div class="col w-100">
            <h2 class="section-title mb-30">Ինչո՞ւ մենք</h2>
            <div class="grid-auto gap-20">
                <div class="feature">
                    <span class="feature__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1 4h14v12H1zM15 9h4l4 4v3h-8z"/><circle cx="5.5" cy="18.5" r="2"/><circle cx="18.5" cy="18.5" r="2"/></svg>
                    </span>
                    <h3 class="title-4 mb-10">Անվճար առաքում</h3>
                    <p class="text-muted"><?= formatPrice(App\Services\CartSummary::FREE_FROM) ?>-ից բարձր պատվերների դեպքում՝ Երևանում</p>
                </div>
                <div class="feature">
                    <span class="feature__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2 4 5v6c0 5 3.4 9.4 8 11 4.6-1.6 8-6 8-11V5z"/><path d="m9 12 2 2 4-4"/></svg>
                    </span>
                    <h3 class="title-4 mb-10">2 տարվա երաշխիք</h3>
                    <p class="text-muted">Բոլոր ապրանքների համար՝ պաշտոնական սպասարկման կենտրոնում</p>
                </div>
                <div class="feature">
                    <span class="feature__icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12a9 9 0 0 1-15.5 6.3L3 16"/><path d="M3 12a9 9 0 0 1 15.5-6.3L21 8"/><path d="M21 3v5h-5M3 21v-5h5"/></svg>
                    </span>
                    <h3 class="title-4 mb-10">14 օր վերադարձ</h3>
                    <p class="text-muted">Չհամապատասխանե՞ց — վերադարձրու առանց հարցերի</p>
                </div>
            </div>
        </div>
    </div>
</section>
