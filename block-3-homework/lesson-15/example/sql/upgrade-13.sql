-- Գրքի տեսակներ — PHP Դաս 13
-- Կատարիր Դաս 09-ի schema.sql + seed.sql-ից հետո․ բազան չի ջնջվում, միայն ավելանում են սյունակներ
SET NAMES utf8mb4;
USE ani_books;

ALTER TABLE books
    ADD COLUMN format   VARCHAR(10)  NOT NULL DEFAULT 'paper' AFTER genre_id,   -- paper · audio · ebook
    ADD COLUMN minutes  SMALLINT UNSIGNED NULL AFTER format,                    -- միայն audio
    ADD COLUMN narrator VARCHAR(100) NULL AFTER minutes,                        -- միայն audio
    ADD COLUMN lent_to  VARCHAR(60)  NULL AFTER narrator;                       -- միայն paper․ ում եմ տվել

-- Երկու աուդիոգիրք, մեկ էլեկտրոնային, մեկ տրված թղթային
UPDATE books SET format = 'audio', minutes = 240, narrator = 'Արմեն Ղազարյան' WHERE slug = 'alqimikosy';
UPDATE books SET format = 'audio', minutes = 660, narrator = 'Էնդի Սերկիս'    WHERE slug = 'hobity';
UPDATE books SET format = 'ebook' WHERE slug = '1984';
UPDATE books SET lent_to = 'Արամ' WHERE slug = 'khenty';
