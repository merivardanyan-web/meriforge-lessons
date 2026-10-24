{{-- ⚠️ ՓՈՐՁՆԱԿԱՆ — PHP Դաս 18, տնային 6․ /lab/escape --}}
<!DOCTYPE html>
<html lang="hy">
<head><meta charset="UTF-8"><title>Փախցնել, թե ոչ</title></head>
<body>
    {{-- @verbatim․ Blade-ը ներսում ոչինչ չի կատարում, տեսնում ենք կոդը որպես տեքստ --}}
    <h2>@verbatim{{ $title }}@endverbatim — փախցված (htmlspecialchars, ինչպես Դաս 05-ի e()-ն)</h2>
    <p>{{ $title }}</p>

    <h2>@verbatim{!! $title !!}@endverbatim — ՉԻ փախցված․ նկարը կբեռնվի, onerror-ը կաշխատի ❌</h2>
    <p>{!! $title !!}</p>
</body>
</html>
