# Դաս 04 · գրանցման ֆորմայի դաշտերը

Սկսիր Դաս 03-ի քո կայքից։ Եթե այն չունես, վերցրու ../lesson-03/example/-ը։
Ավելացրու pages/register.html, իսկ նավիգացիայում՝ «Ակումբ» կետը «Կապ»-ից առաջ։

h1: Գրանցում վեբ ակումբին
p: Ամեն շաբաթ հանդիպում ենք և միասին կառուցում փոքր կայքեր։ Լրացրու ֆորման, և ես կգրեմ քեզ։
form: action="register.html" method="get"

| Խումբ (legend) | Դաշտ (label) | type / թեգ | name | Կանոններ |
|---|---|---|---|---|
| Հաշիվ | Անուն, ազգանուն | text | name | required, minlength 2, autocomplete name |
| Հաշիվ | Էլ. հասցե | email | email | required, placeholder ani@example.com |
| Հաշիվ | Գաղտնաբառ | password | password | required, minlength 8 |
| Անձնական տվյալներ | Տարիք | number | age | min 10, max 99 |
| Անձնական տվյալներ | Ծննդյան օր | date | birthday | — |
| Անձնական տվյալներ | Հեռախոս | tel | phone | pattern 0[0-9]{8}, title «0-ով սկսվող 9 թիվ» |
| Անձնական տվյալներ | Քաղաք | select + optgroup | city | Երևան՝ Կենտրոն, Արաբկիր, Աջափնյակ · Մարզեր՝ Սևան (ընտրված), Գյումրի, Վանաձոր |
| Անձնական տվյալներ | Լուսանկար | file | photo | accept image/* |
| Ակումբ | Ե՞րբ է քեզ հարմար | 4 radio | time | Առավոտյան · Ցերեկը · Երեկոյան (ընտրված) · Հանգստյան օրերին |
| Ակումբ | Ինչն է քեզ հետաքրքրում | 4 checkbox | interest | HTML · CSS · JavaScript · Դիզայն |
| Ակումբ | Ինչու ես ուզում միանալ | textarea | about | rows 4, maxlength 500 |
| — | Համաձայն եմ, որ իմ տվյալները օգտագործվեն միայն ակումբի համար | checkbox | agree | required |

Կոճակ: Գրանցվել (type="submit")
