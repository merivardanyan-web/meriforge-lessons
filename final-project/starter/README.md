# Վերջնական նախագիծ · starter

```
starter/
├── README-template.md     ← քո նախագծի README-ն՝ GitHub-ի համար (պատճենիր որպես README.md)
├── docs/
│   ├── proposal.md        ← Ստուգակետ 1․ թեմա, դերեր, «դժվար ֆունկցիա» 5 նախադասությամբ
│   ├── schema.md          ← Ստուգակետ 1․ աղյուսակներ, կապեր, ON DELETE որոշումներ
│   ├── checkpoints.md     ← 4 ստուգակետի և հանձնման ստուգաթերթերը
│   ├── smoke-test.md      ← Ստուգակետ 4․ ստուգում hosting-ում
│   └── defense-plan.md    ← Ստուգակետ 4․ 10–12 րոպեանոց պաշտպանություն
└── kit/                   ← ոչ պարտադիր հիմք նոր Laravel նախագծի համար
```

## Ինչպես սկսել

1. Կարդա `final-project.html`-ը և ընտրիր թեման
2. Լրացրու `docs/proposal.md`-ը և ուղարկիր դասախոսին **մինչև առաջին ծրագրավորումը**
3. Նոր Laravel նախագիծ (PHP Դաս 18), ապա `docs/`-ը և `README-template.md`-ը պատճենիր պահոց
4. Ամեն ստուգակետից առաջ՝ `docs/checkpoints.md`-ի համապատասխան բաժինը

Ամեն ձևանմուշի կողքին կա լրացված օրինակ՝ `../example-sevan-barber/docs/`-ում։ Նայիր օրինակին **քոնը գրելուց հետո**,
ոչ առաջ․ հակառակ դեպքում քո առաջարկը կլինի վարսավիրանոց՝ այլ անունով։

## kit/ — ինչ կա և ինչ չկա

Կա այն, ինչ նույնն է ամեն նախագծում և արդեն գրել ես ՏեխնոՄարկետում․

| Ֆայլ | Ինչ է | Դաս |
|---|---|---|
| `public/css/global.css` | դասընթացի կմախքը՝ `.row`, `.col`, `.w-*`, `.flex` | HTML 6–14 |
| `public/css/main.css` | չեզոք տոկեններ, կոճակ, ֆորմա, աղյուսակ, header, բուրգեր, toast | HTML 17–19 |
| `layouts/app.blade.php`, `partials/` | layout, skip-link, csrf-token, flash-ի ալերտներ | PHP 19 |
| `components/field`, `alert` | `<x-field>`՝ old(), @error, aria-invalid մեկ տեղում | PHP 19, 21 |
| `errors/403…500` | հայերեն սխալի էջեր, 500-ը՝ առանց layout-ի | PHP 21, 22 |
| `lang/hy/` | validation (`:Attribute` կանոնով), auth, pagination | PHP 21 |
| `app/helpers.php` | `safe_url()`, `slugify()`, `dram()` | PHP 5, 11, 19 |

**Չկա** այն, ինչ քո նախագիծն է՝ մոդելներ, միգրացիաներ, կոնտրոլերներ, Gate-եր, JS, քո էջերը։
Kit-ը օգտագործելը պարտադիր չէ․ եթե ուզում ես CSS-ը գրել զրոյից, դա նույնպես լավ է։

```bash
composer create-project laravel/laravel my-project && cd my-project
composer require laravel/fortify && php artisan fortify:install
cp -r ../starter/kit/* .             # Windows-ում՝ պատճենիր Explorer-ով
# composer.json → "autoload" → "files": ["app/helpers.php"], ապա composer dump-autoload
# config/app.php և config/fortify.php՝ kit/config/*.md
php artisan serve
```

⚠ Չորրորդ շաբաթում փոխիր `main.css`-ի «Շերտ 1» գույները և տառատեսակը։ Չեզոք կապույտը
«kit-ի գույնն է», և դասախոսը այն կճանաչի։
