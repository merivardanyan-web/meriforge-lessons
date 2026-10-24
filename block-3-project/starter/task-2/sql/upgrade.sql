-- ՏեխնոՄարկետ · Առաջադրանք 1-ի բազան → Առաջադրանք 2 (PHP Դաս 13, 16)
-- Տվյալները չեն կորչում․ միայն նոր սյունակներ և աղյուսակներ
SET NAMES utf8mb4;
USE technomarket;

-- ---------- Դաս 13 · ապրանքի տեսակ և քաշ ----------
ALTER TABLE products
    ADD COLUMN type   ENUM('physical', 'digital') NOT NULL DEFAULT 'physical' AFTER image,
    ADD COLUMN weight INT UNSIGNED NOT NULL DEFAULT 0 AFTER type;      -- գրամ

UPDATE products SET weight = 1400 WHERE slug = 'noutbuq-pro-14';
UPDATE products SET weight = 1100 WHERE slug = 'noutbuq-air-13';
UPDATE products SET weight = 2100 WHERE slug = 'noutbuq-studio-16';
UPDATE products SET weight = 1800 WHERE slug = 'noutbuq-lite-15';
UPDATE products SET weight = 6200 WHERE slug = 'monitor-27-4k';             -- 5 կգ-ից ծանր → առաքումը 3000 ֏
UPDATE products SET weight = 180  WHERE slug = 'smartfon-x-128';
UPDATE products SET weight = 480  WHERE slug = 'planshet-11';
UPDATE products SET weight = 900  WHERE slug = 'steghnashar-mekhanikakan';
UPDATE products SET weight = 250  WHERE slug = 'akanjakal-studio';
UPDATE products SET weight = 90   WHERE slug = 'mknik-anlar';
UPDATE products SET weight = 400  WHERE slug = 'bardzrakhos-mini';
UPDATE products SET weight = 60   WHERE slug = 'zhamatsuyts-fit';

-- Թվային ապրանք․ պահեստ չունի, չի առաքվում
INSERT INTO products (category_id, name, slug, specs, description, price, discount, stock, image, type) VALUES
(5, 'Հակավիրուս Shield · 1 տարի', 'hakavirus-shield-1-tari', 'Լիցենզիա 3 սարքի համար',
 'Թվային լիցենզիա․ բանալին կուղարկենք էլ. փոստով պատվերից անմիջապես հետո։ Աշխատում է Windows, macOS և Android սարքերում։',
 15000, 0, 0, 'antivirus.jpg', 'digital');

-- ---------- Դաս 16 · օգտագործողներ ----------
CREATE TABLE users (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email         VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    name          VARCHAR(100) NOT NULL,
    role          ENUM('admin', 'manager', 'customer') NOT NULL DEFAULT 'customer',
    created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Պատվերը կապվում է գնորդի հետ։ Հյուրինը՝ NULL, իսկ ջնջված հաշվի պատվերները մնում են
ALTER TABLE orders
    ADD COLUMN user_id INT UNSIGNED NULL AFTER id,
    ADD CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL;

-- Ադմինը, մենեջերը և գնորդը՝ Դաս 16-ի ձևով.
--   php -r "echo password_hash('ձեր_գաղտնաբառը', PASSWORD_DEFAULT);"
-- INSERT INTO users (email, password_hash, name, role) VALUES ('admin@technomarket.test', '$2y$12$...', 'Ադմին', 'admin');
