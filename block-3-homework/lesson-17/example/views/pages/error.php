<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <div class="wrapper">
                <p class="meta">Սխալ <?= (int) $code ?></p>
                <h1><?= match ($code) { 404 => 'Էջը չի գտնվել', 403 => 'Մուտքը փակ է', 419 => 'Էջը հնացել է', default => 'Ինչ-որ բան այն չէ' } ?></h1>
                <p class="lead"><?= e($message) ?></p>
                <p><a href="/" class="btn btn--primary">Դարակ</a></p>
            </div>
        </div>
    </div>
</section>
