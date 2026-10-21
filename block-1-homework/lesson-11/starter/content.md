# Դաս 11 · Flexbox I

Սկսիր Դաս 10-ի քո կայքից։ Եթե այն չունես, վերցրու ../lesson-10/example/-ը։

## Header
- Կայքի անունը՝ <a href="index.html" class="logo">Անի Սարգսյան</a> (p-ի փոխարեն)
- .row՝ align-center justify-between, երկու .col առանց .w-* դասերի
- Մենյուի ul՝ class="menu flex align-center"

## Գլխավոր էջ՝ «Իմ նախագծերը»
- Վերնագրի տող՝ .row align-center justify-between․ ձախում h2, աջում հղում «Բոլոր նախագծերը» → pages/schedule.html
- Երեք քարտ (w-33), տեքստերը միտումնավոր տարբեր երկարության

| Նկար | Վերնագիր | Տեքստ | Պիտակներ | Կոճակ |
|---|---|---|---|---|
| project-site.jpg | Այս կայքը | Սկսվեց մեկ էջից Դաս 1-ում։ Հիմա ունի տասը էջ, ոճեր և դասավորության համակարգ, իսկ ամեն դասից հետո ավելանում է մի նոր բան։ | HTML, CSS | Կարդալ բլոգը → pages/blog.html |
| project-form.jpg | Գրանցման ֆորմա | Ֆորմա երեք խմբով, վալիդացիայով և label-ներով, որը կարելի է անցնել միայն ստեղնաշարով։ | HTML, CSS | Բացել → pages/register.html |
| project-schedule.jpg | Դասացուցակ | Աղյուսակ rowspan-ով։ | HTML | Բացել → pages/schedule.html |

## Footer՝ երեք սյուն
| Սյուն | Բովանդակություն |
|---|---|
| w-40 | Անի Սարգսյան (class="logo") · Սովորում եմ վեբ ծրագրավորում և գրում եմ այդ մասին։ |
| w-30 | nav aria-label="Footer-ի հղումներ"՝ ul class="flex flex-column"․ Գլխավոր, Բլոգ, Կապ |
| w-30 | address (էլ. հասցե, Սևան, Հայաստան) և © տող՝ wrapper class="flex flex-column" |
