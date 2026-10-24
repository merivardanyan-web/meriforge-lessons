-- Անիի ակումբը · հարցումներ — PHP Դաս 08
-- Կատարիր մեկ-մեկ՝ HeidiSQL-ում ընտրիր հարցումը և Ctrl+F9
SET NAMES utf8mb4;
USE ani_books;

-- ============ Տնային 2 · ևս 5 գիրք, մեկը՝ էմոջիով ============
INSERT INTO books (title, slug, author, year, pages, read_pages, genre, rating, color, link, created_at) VALUES
('Խենթը', 'khenty', 'Րաֆֆի', 1881, 300, 300, 'պատմավեպ', 4, '#7a2f2f', NULL, '2026-09-25 10:00:00'),
('Անուշ', 'anush', 'Հովհաննես Թումանյան', 1902, 64, 64, 'պոեզիա', 5, '#2f6b7a', NULL, '2026-10-01 10:00:00'),
('Մեծ սպասումներ', 'mets-spasumner', 'Չարլզ Դիքենս', 1861, 544, 0, 'վեպ', 0, '#4a5a2f', NULL, '2026-10-05 10:00:00'),
('Կարմիր գլխարկ 🐺', 'karmir-glkhark', 'Շառլ Պերո', 1697, 24, 24, 'հեքիաթ', 4, '#a33a3a', NULL, '2026-10-10 10:00:00'),
('Հարրի Փոթերը և փիլիսոփայական քարը', 'harri-potery-ev-pilisopayakan-qary', 'Ջ. Ք. Ռոուլինգ', 1997, 332, 50, 'ֆանտաստիկա', 0, '#5a3f7a', NULL, '2026-10-14 10:00:00');

-- 1. Ամբողջ դարակը՝ այբբենական կարգով
SELECT id, title, author, pages
FROM books
ORDER BY title;

-- 2. Դեռ չսկսված գրքերը
SELECT title, pages
FROM books
WHERE read_pages = 0;

-- 3. Կարդում եմ՝ սկսել եմ, բայց չեմ ավարտել
SELECT title, read_pages, pages
FROM books
WHERE read_pages > 0 AND read_pages < pages;

-- 4. Րաֆֆիի բոլոր գրքերը
SELECT title, year
FROM books
WHERE author LIKE '%Րաֆֆի%'
ORDER BY year;

-- 5. Ամենահաստ 3 գիրքը
SELECT title, pages
FROM books
ORDER BY pages DESC
LIMIT 3;

-- 6. 5 աստղ ստացած գրքերը՝ նորից հին
SELECT title, rating, created_at
FROM books
WHERE rating = 5
ORDER BY created_at DESC;

-- 7. Էջ 2՝ ամեն էջում 5 գիրք (Դաս 10-ի էջավորման համար)
SELECT id, title
FROM books
ORDER BY id
LIMIT 5 OFFSET 5;

-- 8. Քանի՞ գիրք կա դարակում
SELECT COUNT(*) AS total
FROM books;

-- 9. Կարդացի ևս 20 էջ «Սամվել»-ից․ նախ SELECT, որ համոզվես՝ ճիշտ տողն է
SELECT id, title, read_pages FROM books WHERE slug = 'samvel';

UPDATE books
SET read_pages = read_pages + 20
WHERE slug = 'samvel';

-- 10. Գնահատեցի «Հոբիտը»-ն և ուղղեցի տարին
UPDATE books
SET rating = 4, year = 1937
WHERE slug = 'hobity';

-- ============ Տնային 3 · = NULL և IS NULL ============
-- Ավելացնենք մեկ գիրք առանց տարեթվի
INSERT INTO books (title, slug, author, pages, genre)
VALUES ('Հին աստվածներ', 'hin-astvatsner', 'Լևոն Շանթ', 120, 'պատմավեպ');

-- 0 տող․ NULL-ը ոչնչի «հավասար» չէ, նույնիսկ NULL-ի
SELECT title FROM books WHERE year = NULL;

-- 1 տող․ ճիշտ ձևը
SELECT title FROM books WHERE year IS NULL;

-- ============ Տնային 4 · միջակայք՝ հաստից բարակ ============
SELECT title, pages
FROM books
WHERE is_active = 1 AND pages BETWEEN 100 AND 350
ORDER BY pages DESC;

-- ============ Տնային 5 · թաքցնել չսկսված գրքերը, որ ավելացվել են մինչև հոկտեմբեր ============
-- Նախ՝ SELECT նույն WHERE-ով։ Եթե ցուցակը ճիշտ է՝ նոր UPDATE
SELECT id, title, created_at
FROM books
WHERE read_pages = 0 AND created_at < '2026-10-01';

UPDATE books
SET is_active = 0
WHERE read_pages = 0 AND created_at < '2026-10-01';

-- ============ Տնային 6 · առաջընթացը տոկոսով, միայն կեսից քիչ կարդացածները ============
-- WHERE-ում percent-ը դեռ չկա (SELECT-ը կատարվում է WHERE-ից հետո), ուստի բանաձևը կրկնում ենք
SELECT title, read_pages, pages,
       ROUND(read_pages * 100 / pages) AS percent
FROM books
WHERE read_pages > 0 AND ROUND(read_pages * 100 / pages) < 50
ORDER BY percent;

-- ============ Մաքրում ============
-- Ջնջում ենք փորձնական գիրքը։ DELETE-ը՝ միշտ WHERE-ով, նախ նույն SELECT-ը
SELECT id, title FROM books WHERE slug = 'hin-astvatsner';
DELETE FROM books WHERE slug = 'hin-astvatsner';
