-- Անիի ակումբը · կապեր և հաշվարկներ — PHP Դաս 09
SET NAMES utf8mb4;
USE ani_books;

-- 1. Գրքերը՝ ժանրի անունով (INNER JOIN․ առանց ժանրի գիրքը դուրս կմնար)
SELECT b.title, g.name AS genre
FROM books b
JOIN genres g ON g.id = b.genre_id
ORDER BY g.name, b.title;

-- 2. Բոլոր ժանրերը և գրքերի քանակը (LEFT JOIN․ «դետեկտիվ»-ը՝ 0-ով)
SELECT g.name, COUNT(b.id) AS books
FROM genres g
LEFT JOIN books b ON b.genre_id = g.id
GROUP BY g.id, g.name
ORDER BY books DESC, g.name;

-- 3. Էջերը ժանրերով՝ ընդամենը և կարդացած
SELECT g.name, SUM(b.pages) AS pages, SUM(b.read_pages) AS read_pages
FROM genres g
JOIN books b ON b.genre_id = g.id
GROUP BY g.id, g.name
ORDER BY pages DESC;

-- 4. Ժանրեր, որոնցում 2-ից ավելի գիրք կա (HAVING՝ GROUP BY-ից հետո)
SELECT g.name, COUNT(*) AS books
FROM genres g
JOIN books b ON b.genre_id = g.id
GROUP BY g.id, g.name
HAVING COUNT(*) > 2;

-- 5. Վերջին 5 ընթերցանության օրը՝ գրքի վերնագրով
SELECT rs.read_on, b.title, rs.pages
FROM reading_sessions rs
JOIN books b ON b.id = rs.book_id
ORDER BY rs.read_on DESC, rs.id DESC
LIMIT 5;

-- 6. Ամեն գիրքը՝ քանի օր եմ կարդացել և քանի էջ (LEFT JOIN․ չսկսածները՝ 0 օր և NULL էջ)
-- COUNT(rs.id)-ը NULL-երը չի հաշվում, իսկ ոչնչի SUM-ը NULL է, ոչ 0
SELECT b.title, COUNT(rs.id) AS days, SUM(rs.pages) AS pages
FROM books b
LEFT JOIN reading_sessions rs ON rs.book_id = b.id
GROUP BY b.id, b.title
ORDER BY pages DESC;

-- 7. Հոկտեմբերի ընթերցանությունը
SELECT COUNT(*) AS days, SUM(pages) AS pages
FROM reading_sessions
WHERE read_on BETWEEN '2026-10-01' AND '2026-10-31';

-- 8. Ստուգում․ read_pages-ը համընկնո՞ւմ է սեսիաների գումարի հետ (0 տող = ամեն ինչ կարգին է)
SELECT b.title, b.read_pages, SUM(rs.pages) AS sessions_sum
FROM books b
JOIN reading_sessions rs ON rs.book_id = b.id
GROUP BY b.id, b.title, b.read_pages
HAVING SUM(rs.pages) <> b.read_pages;

-- ============ Տնային 2 · նոր օր՝ երեք գրքով ============
INSERT INTO reading_sessions (book_id, pages, read_on, note) VALUES
(2, 30, '2026-10-18', 'Ճանապարհին'),
(7, 25, '2026-10-18', NULL),
(12, 40, '2026-10-18', 'Մինչև քնելը');

UPDATE books SET read_pages = read_pages + 30 WHERE id = 2;
UPDATE books SET read_pages = read_pages + 25 WHERE id = 7;
UPDATE books SET read_pages = read_pages + 40 WHERE id = 12;

-- Ամեն օրը՝ քանի գիրք և քանի էջ
SELECT read_on, COUNT(*) AS books, SUM(pages) AS pages
FROM reading_sessions
GROUP BY read_on
ORDER BY read_on DESC
LIMIT 5;

-- ============ Տնային 3 · ջնջել գիրք, որը սեսիաներ ունի ============
-- Սխալ 1451․ Cannot delete or update a parent row․ RESTRICT-ը պաշտպանում է պատմությունը
DELETE FROM books WHERE id = 2;

-- ============ Տնային 4 · ջնջել ժանր ============
DELETE FROM genres WHERE slug = 'patmvatsq';

-- Գիրքը մնաց, genre_id-ն դարձավ NULL (ON DELETE SET NULL)
SELECT title, genre_id FROM books WHERE slug = 'giqory';

-- Վերադարձնում ենք։ Ժանրը կստանա ՆՈՐ id (8, ոչ 3)․ AUTO_INCREMENT-ը հետ չի գնում
INSERT INTO genres (name, slug) VALUES ('պատմվածք', 'patmvatsq');

SELECT id, name FROM genres WHERE slug = 'patmvatsq';

UPDATE books SET genre_id = 8 WHERE slug = 'giqory';

-- ============ Տնային 5 · ON ... AND և WHERE ============
-- ON-ում․ ֆիլտրում է միայն կցվող գրքերը, ժանրերը բոլորը մնում են («դետեկտիվ»՝ 0)
SELECT g.name, COUNT(b.id) AS active_books
FROM genres g
LEFT JOIN books b ON b.genre_id = g.id AND b.is_active = 1
GROUP BY g.id, g.name
ORDER BY g.name;

-- WHERE-ում․ ֆիլտրում է ամբողջ արդյունքը, «դետեկտիվ»-ը անհետանում է (NULL = 1 → ոչ)
SELECT g.name, COUNT(b.id) AS active_books
FROM genres g
LEFT JOIN books b ON b.genre_id = g.id
WHERE b.is_active = 1
GROUP BY g.id, g.name
ORDER BY g.name;

-- ============ Տնային 6 · ամենաշատ կարդացած 3 գիրքը ============
SELECT b.title, SUM(rs.pages) AS pages
FROM reading_sessions rs
JOIN books b ON b.id = rs.book_id
GROUP BY b.id, b.title
ORDER BY pages DESC
LIMIT 3;
