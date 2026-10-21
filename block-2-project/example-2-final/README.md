# ՏեխնոՄարկետ

Տեխնիկայի խանութ՝ HTML/CSS բլոկի 9 էջը, որոնք կենդանացել են JavaScript-ով (Բլոկ 2-ի նախագիծ)։

**Դեմո՝** https://username.github.io/technomarket/

## Գործարկում
Մոդուլներն ու `fetch`-ը չեն աշխատում `file://`-ով։ Բացիր Live Server-ով (VS Code) կամ GitHub Pages-ում։

## Հնարավորություններ
- Ապրանքները `data/products.json`-ից՝ բեռնման, սխալի և դատարկ վիճակներով
- Կատալոգ՝ ֆիլտրեր (ցուցակները կառուցվում են տվյալներից), որոնում, դասավորում, էջավորում, `?cat=` և `?q=`
- Ապրանքի էջ `?id=`-ով՝ պատկերասրահ, բնութագրեր, նման ապրանքներ
- Զամբյուղ (`Cart` դաս) և հավանածներ (`Wishlist`՝ `Set`-ով), պահպանում localStorage-ում
- Պատվերի, մուտքի և գրանցման ֆորմաների ստուգում (`ValidationError`), գաղտնաբառի ուժ
- Պատվերի համարը շնորհակալության էջում (sessionStorage)
- Մուգ/բաց թեմա՝ ընտրության պահպանմամբ, բուրգերը փակվում է Escape-ով

## Կառուցվածք
```
js/
├── config.js        հաստատուններ (Object.freeze)
├── api.js           բոլոր հարցումները, քեշ
├── errors.js        ApiError, NotFoundError, ValidationError
├── storage.js       save / load
├── utils.js         formatPrice, debounce, paginate
├── validation.js    ստուգման կանոններ
├── store.js         մեկ cart և մեկ wishlist ամբողջ կայքի համար
├── models/          Cart, Wishlist
├── ui/              layout, header, theme, toast, cards, cart-modal, totals, password
├── catalog/         filters (մաքուր ֆունկցիաներ)
└── pages/           ամեն էջի մուտքի կետը
```

## Հայտնի սահմանափակումներ
- Պատվերը, մուտքը և գրանցումը չեն գնում սերվեր։ PHP բլոկում (PHP Դաս 16–17)։
- Header-ը և footer-ը կրկնվում են 9 էջում։ PHP-ի layout-ում (PHP Դաս 15)։
- Ֆիլտրերը չեն գրվում հասցեում, ուստի ֆիլտրված կատալոգի հղումը կիսել հնարավոր չէ։
- Գները ձևավորվում են `ru-RU` լոկալով, որովհետև Chrome-ը չունի `hy-AM`-ը։

## Lighthouse
Performance — · Accessibility — · Best Practices — · SEO —
