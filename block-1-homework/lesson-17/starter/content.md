# Դաս 17 · կոմպոնենտներ

Սկսիր Դաս 16-ի քո կայքից։ Եթե այն չունես, վերցրու ../lesson-16/example/-ը։

## components.html (կայքի արմատում, հղումը՝ footer-ում «Կոմպոնենտներ»)
Բաժիններ՝ Վերնագրեր · Կոճակներ · Քարտեր · Ստվերներ · Ցուցակներ և պիտակներ

## Կոճակներ
Բազա .btn + տեսակներ՝ --primary, --outline, --ghost, --light, --danger
Չափեր՝ --sm, --lg, --block
Վիճակներ՝ :hover, :focus-visible, :active, :disabled
Կայքում՝ ամեն .btn-ին ավելացրու տեսակը (header-ի և քարտերի կոճակները՝ btn btn--primary btn--sm)

## Ստվերներ (:root)
--shadow-sm 0 1px 3px rgba(0,0,0,.08) · --shadow-md 0 4px 12px rgba(0,0,0,.10) · --shadow-lg 0 10px 28px rgba(0,0,0,.14)

## Քարտ
.card--featured՝ «Այս կայքը» քարտը Նախագծերի էջում
.card--horizontal՝ components.html-ում (նկարը ձախում 40%, տեքստը աջում)

## ::before և ::after
- .section-title::after՝ 48 × 3 գիծ բոլոր բաժինների h2-երի տակ
- .check-list՝ «Ինչ եմ սիրում» (about) և «Ինչ է ակումբը» (register)
- .crumbs՝ ներքին էջերի վերևում․ Գլխավոր / … / ընթացիկ էջ
