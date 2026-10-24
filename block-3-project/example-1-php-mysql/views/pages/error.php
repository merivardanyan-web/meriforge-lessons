<?php /** @var int $code  @var string $message */ ?>
<section class="status">
    <div class="row">
        <div class="col w-100">
            <div class="wrapper">
                <p class="status__code" aria-hidden="true"><?= (int) $code ?></p>
                <h1 class="mb-10"><?= $code === 404 ? 'Էջը չի գտնվել' : 'Ինչ-որ բան այն չէ' ?></h1>
                <p class="text-muted mb-30"><?= e($message) ?></p>
                <div class="flex gap-10 flex-wrap justify-center">
                    <a href="/" class="btn btn--primary">Գլխավոր էջ</a>
                    <a href="/catalog.php" class="btn btn--ghost">Կատալոգ</a>
                </div>
            </div>
        </div>
    </div>
</section>
