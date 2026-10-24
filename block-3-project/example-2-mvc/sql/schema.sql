-- ՏեխնոՄարկետ · բազայի սխեմա (PHP Դաս 8, 9, 11, 13, 16)
-- Առաջադրանք 1-ի սխեման + type/weight (Դաս 13), users (Դաս 16), orders.user_id
-- HeidiSQL → File → Run SQL file, կամ՝ mysql -u root < sql/schema.sql
-- ⚠️ Ջնջում է հին բազան։ Հետո կատարիր seed.sql-ը

SET NAMES utf8mb4;   -- ⚠️ առանց սրա mysql տերմինալը կարող է հայերենը պահել «Õ†Õ¸...» տեսքով

DROP DATABASE IF EXISTS technomarket;

CREATE DATABASE technomarket
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE technomarket;

-- ---------- Կատեգորիաներ — Դաս 9 ----------
CREATE TABLE categories (
    id    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name  VARCHAR(100) NOT NULL,
    slug  VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- ---------- Ապրանքներ — Դաս 8 ----------
CREATE TABLE products (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id INT UNSIGNED NULL,
    name        VARCHAR(200)  NOT NULL,
    slug        VARCHAR(200)  NOT NULL UNIQUE,
    specs       VARCHAR(255)  NULL,
    description TEXT          NULL,
    price       DECIMAL(10,2) NOT NULL,
    discount    TINYINT UNSIGNED NOT NULL DEFAULT 0,
    stock       INT UNSIGNED  NOT NULL DEFAULT 0,
    image       VARCHAR(255)  NULL,
    type        ENUM('physical', 'digital') NOT NULL DEFAULT 'physical',   -- Դաս 13
    weight      INT UNSIGNED  NOT NULL DEFAULT 0,                         -- գրամ, Դաս 13
    is_active   TINYINT(1)    NOT NULL DEFAULT 1,
    created_at  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_products_category
        FOREIGN KEY (category_id) REFERENCES categories(id)
        ON DELETE SET NULL,

    INDEX idx_active_price (is_active, price)          -- կատալոգի համար — Դաս 9
) ENGINE=InnoDB;

-- ---------- Օգտագործողներ — Դաս 16 ----------
CREATE TABLE users (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email         VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    name          VARCHAR(100) NOT NULL,
    role          ENUM('admin', 'manager', 'customer') NOT NULL DEFAULT 'customer',
    created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------- Պատվերներ — Դաս 9 ----------
-- Դասի աղյուսակին ավելացրել ենք checkout-ի դաշտերը (Բլոկ 1-ի ֆորմայից)
CREATE TABLE orders (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id       INT UNSIGNED NULL,                    -- հյուրը նույնպես կարող է պատվիրել
    customer_name VARCHAR(100) NOT NULL,
    email         VARCHAR(190) NULL,
    phone         VARCHAR(30)  NOT NULL,
    delivery      ENUM('courier', 'pickup', 'region') NOT NULL DEFAULT 'courier',
    region        VARCHAR(40)  NULL,
    address       VARCHAR(255) NULL,
    payment       ENUM('cash', 'card') NOT NULL DEFAULT 'cash',
    comment       VARCHAR(500) NULL,
    shipping      DECIMAL(10,2) NOT NULL DEFAULT 0,
    total         DECIMAL(10,2) NOT NULL,
    status        ENUM('new', 'paid', 'shipped', 'cancelled') NOT NULL DEFAULT 'new',
    created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE order_items (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id   INT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    quantity   INT UNSIGNED NOT NULL,
    price      DECIMAL(10,2) NOT NULL,                 -- գինը ԳՆՄԱՆ ՊԱՀԻՆ — Դաս 9

    FOREIGN KEY (order_id)   REFERENCES orders(id)   ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT
) ENGINE=InnoDB;
