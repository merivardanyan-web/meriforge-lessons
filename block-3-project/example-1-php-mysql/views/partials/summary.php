<?php /** Ամփոփում՝ զամբյուղի և պատվերի էջերում — գրված է մեկ անգամ  @var array $summary */ ?>
<ul class="summary__list">
    <li><span>Ապրանքներ (<?= $summary['count'] ?>)</span><span><?= formatPrice($summary['full']) ?></span></li>
    <?php if ($summary['discount'] > 0): ?>
        <li><span>Զեղչ</span><span class="text-success">−<?= formatPrice($summary['discount']) ?></span></li>
    <?php endif; ?>
    <li><span>Առաքում</span><span><?= $summary['shipping'] === 0 ? 'Անվճար' : formatPrice($summary['shipping']) ?></span></li>
    <li class="summary__total"><span>Ընդամենը</span><span><?= formatPrice($summary['total']) ?></span></li>
</ul>
