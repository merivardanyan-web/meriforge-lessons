<section class="page-head">
    <div class="row">
        <div class="col w-100">
            <div class="wrapper">
                <h1>Կարդալու ցուցակ</h1>
                <p class="lead"><?= count($items) ?> գիրք · մնացել է <?= e(formatPages($pagesLeft)) ?><?php if ($listening > 0): ?> · լսելու՝ <?= e((new App\Services\DurationFormatter())->format($listening)) ?><?php endif; ?></p>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="row">
        <div class="col w-100">
            <?php if ($items === []): ?>
                <p class="empty">Ցուցակը դատարկ է։ <a href="/">Ընտրիր գիրք դարակից</a></p>
            <?php else: ?>
                <div class="grid-auto gap-20">
                    <?php foreach ($items as $item): ?>
                        <div class="list-item">
                            <?= render('partials/book-card', ['book' => $item->book, 'back' => '/list']) ?>

                            <!-- Դաս 12, տնային 7․ ReadingList::setPerDay() վալիդացիայով -->
                            <form method="post" action="/list/goal" class="goal-form">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= $item->book->id ?>">
                                <label for="per-day-<?= $item->book->id ?>">Օրական</label>
                                <input type="number" id="per-day-<?= $item->book->id ?>" name="per_day" min="1" max="200" value="<?= $item->perDay ?>">
                                <span>էջ</span>
                                <button type="submit" class="btn btn--ghost btn--sm">Պահել</button>
                            </form>
                            <p class="meta">Կավարտես <strong><?= $item->daysLeft() ?> օրում</strong></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
