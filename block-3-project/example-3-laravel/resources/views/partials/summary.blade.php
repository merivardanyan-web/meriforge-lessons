{{-- Ամփոփում՝ զամբյուղի և պատվերի էջերում — գրված է մեկ անգամ --}}
<ul class="summary__list">
    <li><span>Ապրանքներ ({{ $summary['count'] }})</span><span>{{ money($summary['full']) }}</span></li>
    @if ($summary['discount'] > 0)
        <li><span>Զեղչ</span><span class="text-success">−{{ money($summary['discount']) }}</span></li>
    @endif
    <li><span>Առաքում</span><span>{{ $summary['shipping'] === 0 ? 'Անվճար' : money($summary['shipping']) }}</span></li>
    <li class="summary__total"><span>Ընդամենը</span><span>{{ money($summary['total']) }}</span></li>
</ul>
