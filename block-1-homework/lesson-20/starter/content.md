# Դաս 20 · Grid

Սկսիր Դաս 19-ի քո կայքից։ Եթե այն չունես, վերցրու ../lesson-19/example/-ը։

## global.css
.grid · .grid-auto (repeat(auto-fit, minmax(min(260px, 100%), 1fr))) · .grid-2 · .grid-3 · .col-full

## Որտեղ Grid
| Բաժին | Ինչ անել |
|---|---|
| Նախագծերի քարտեր (index, projects) | .col w-33 w-md-50 w-sm-100-ի փոխարեն՝ մեկ .col w-100 և ներսում <div class="grid-auto gap-20"> |
| «Այս կայքը» քարտը projects-ում | .card--featured՝ grid-column: span 2 |
| Պատկերասրահ | .gallery՝ 3 սյուն, gap 20px, առաջին նկարը՝ 2 × 2 (grid-column: span 2; grid-row: span 2) · ≤ 768px՝ 2 սյուն · ≤ 576px՝ 1 սյուն |
| Բլոգ | .blog-layout՝ grid-template-areas "posts aside", 1fr 300px · ≤ 768px՝ "posts" / "aside" |
| Գրանցման ֆորմա | .form-grid՝ 140px minmax(0, 1fr)․ պիտակները ձախում, դաշտերը աջում · ≤ 576px՝ մեկ սյուն |
