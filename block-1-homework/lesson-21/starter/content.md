# Դաս 21 · անցումներ և անիմացիաներ

Սկսիր Դաս 20-ի քո կայքից։ Եթե այն չունես, վերցրու ../lesson-20/example/-ը։

## Տոկեն
--transition: .2s ease (:root)

## Անցումներ
- Բոլոր կոճակները՝ background-color, color, border-color, transform
- Քարտ hover-ին՝ translateY(-4px) + --shadow-md
- Մենյուի հղումներ՝ ընդգծում, որը աճում է կենտրոնից (::after, scaleX, transform-origin: center)

## Անիմացիաներ (@keyframes)
- fade-up՝ hero-ի h1, .lead և կոճակների տող, 0.1s հետաձգումներով (.delay-1, .delay-2)
- pulse՝ «Նոր» պիտակը, 3 անգամ
- pop՝ մոդալ պատուհանը thanks.html-ում
- shimmer՝ բեռնման կմախք բլոգի aside-ում «Հաջորդ գրառումը»․ վերնագիր 70%, տող, կարճ տող 50%

## Շարժման հարգանք
global.css-ում՝ prefers-reduced-motion բլոկը
