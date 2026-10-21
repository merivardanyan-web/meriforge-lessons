# Դաս 10 · կառուցվածքի համակարգը

Սկսիր Դաս 09-ի քո կայքից։ Եթե այն չունես, վերցրու ../lesson-09/example/-ը։
Ամբողջ կայքը վերակառուցիր main > section > .row > .col > .wrapper համակարգով։

## Չափեր (main.css)
section՝ padding 40px 0 · .row՝ padding 0 50px · .col՝ padding 10px
header և footer .row՝ padding-top/bottom 10px
Դաս 08-ի main { max-width; margin: 0 auto } այլևս պետք չէ

## Սյուներ ըստ էջի
| Էջ | Բաժին | Սյուներ |
|---|---|---|
| Բոլորը | header | w-30 (անուն) + w-70 (մենյու, text-align: right) |
| Բոլորը | footer | w-40 (հասցե) + w-30 (մենյու) + w-30 (©, text-right) |
| index | #intro | w-70 (վերնագիր, տեքստ) + w-30 (դիմանկար, text-center) |
| index | #learning | w-100 (h2) · w-50 (HTML) + w-50 (CSS) |
| index | #projects | w-100 (h2) · w-50 + w-50 (քարտեր) |
| index | #resources | w-100 |
| about | #about-me | w-30 (դիմանկար) + w-70 (տեքստ) |
| about | #likes | w-40 (ցուցակ) + w-60 (քաղաքը) |
| blog | #posts | w-100 (h1) · w-70 (գրառումներ) + w-30 (aside) |
| schedule, compare | — | w-100 |
| register | #register | w-70 (ֆորմա) + w-30 («Ինչ է ակումբը») |
| contact | #contact | w-100 (h1) · w-50 + w-50 |

## Նոր տեքստ register-ի կողային սյան համար
h2 «Ինչ է ակումբը»
- Հանդիպում՝ շաբաթը մեկ անգամ
- Փոքր խմբեր՝ մինչև 8 հոգի
- Ամեն հանդիպումից հետո՝ մեկ փոքր կայք
p: Անվճար է։ Պետք է միայն նոութբուք։

## Տեսք
.box՝ padding 20px, ֆոն --surface, եզրագիծ 1px #e0e0e0, կլորացում 12px — դիր .wrapper-ի վրա, ոչ .col-ի
Ֆոնով բաժիններ՝ class="bg-surface" section-ի վրա (#intro, #projects, #about-me, #conclusion)
