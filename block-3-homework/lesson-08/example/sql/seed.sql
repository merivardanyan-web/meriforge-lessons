-- Անիի դարակը · 7 գիրք data.php-ից — PHP Դաս 08
-- Կատարիր schema.sql-ից հետո
SET NAMES utf8mb4;
USE ani_books;

-- ⚠️ Հոբիտի հղումը դիտավորյալ վտանգավոր է (Դաս 05)․ բազայից եկածը նույնպես վստահելի չէ
INSERT INTO books (title, slug, author, year, pages, read_pages, genre, rating, color, link, created_at) VALUES
('Փոքրիկ իշխանը', 'poqrik-ishkhany', 'Անտուան դը Սենտ-Էքզյուպերի', 1943, 96, 96, 'հեքիաթ', 5, '#2f5d8a', 'https://hy.wikipedia.org/w/index.php?search=Փոքրիկ+իշխանը', '2026-09-01 10:00:00'),
('Սամվել', 'samvel', 'Րաֆֆի', 1886, 560, 140, 'պատմավեպ', 0, '#8a3b2f', 'https://hy.wikipedia.org/w/index.php?search=Սամվել+Րաֆֆի', '2026-09-03 10:00:00'),
('Գիքորը', 'giqory', 'Հովհաննես Թումանյան', 1907, 40, 40, 'պատմվածք', 5, '#5b7a3a', 'https://hy.wikipedia.org/w/index.php?search=Գիքորը', '2026-09-05 10:00:00'),
('1984', '1984', 'Ջորջ Օրուել', 1949, 328, 0, 'վեպ', 0, '#3d3d55', 'https://en.wikipedia.org/wiki/Nineteen_Eighty-Four', '2026-09-10 10:00:00'),
('Ալքիմիկոսը', 'alqimikosy', 'Պաուլո Կոելյո', 1988, 208, 208, 'վեպ', 3, '#b07a1f', 'https://en.wikipedia.org/wiki/The_Alchemist_(novel)', '2026-09-12 10:00:00'),
('Վերք Հայաստանի', 'verq-hayastani', 'Խաչատուր Աբովյան', 1858, 320, 90, 'պատմավեպ', 0, '#6b4a7a', 'https://hy.wikipedia.org/w/index.php?search=Վերք+Հայաստանի', '2026-09-15 10:00:00'),
('Հոբիտը', 'hobity', 'Ջ. Ռ. Ռ. Թոլքին', NULL, 310, 120, 'ֆանտաստիկա', 0, '#3f6b5a', 'javascript:alert(document.cookie)', '2026-09-20 10:00:00');
