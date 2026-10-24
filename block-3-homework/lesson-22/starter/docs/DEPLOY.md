# Deploy · Դաս 22

## Մինչև deploy

- [ ] `git log --all --oneline -- .env` բոլոր պահոցներում՝ դատարկ
- [ ] `.env.example`-ում ոչ մի իսկական գաղտնաբառ
- [ ] `APP_DEBUG=false`, `APP_ENV=production`
- [ ] Document root-ը՝ `public/`, ոչ նախագծի արմատը (Դաս 15 · /.env → 404)

## Hosting-ում

```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate --force
php artisan migrate --force
php artisan db:seed --force                    # ժանրեր և գրքեր
php artisan db:seed --class=DemoSeeder --force # admin և demo հաշիվներ .env-ից
php artisan storage:link
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

## Smoke test (10 կետ)

1. Գլխավոր էջը բացվում է HTTPS-ով, CSS-ը բեռնված է
2. `/.env` → 404, `/storage/../.env` → 404
3. Որոնում «Րաֆֆի» → 2 գիրք
4. `/genre/patmavep` → 3 գիրք, էջավորումը աշխատում է
5. «+ Կարդալու ցուցակ» → հաշվիչը փոխվում է առանց էջի վերբեռնման
6. Սխալ գաղտնաբառ → «Սխալ էլ. հասցե կամ գաղտնաբառ», 6-րդ փորձը՝ արգելափակված
7. Demo հաշիվով՝ ադմինը բացվում է, «Ջնջել» կոճակ չկա
8. Անդամով `/admin/books` → 403 (մեր էջը, ոչ Laravel-ի լռելյայնը)
9. Շապիկի վերբեռնում → նկարը երևում է քարտում
10. Գոյություն չունեցող հասցե → 404 էջ՝ հայերեն, առանց stack trace-ի
