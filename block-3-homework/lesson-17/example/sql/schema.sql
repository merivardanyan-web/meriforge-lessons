-- Անիի ակումբը · բազա — PHP Դաս 09
-- Ամբողջ սխեման մեկ ֆայլում (տնային 7)․ կատարելուց հետո բազան՝ զրոյից
-- HeidiSQL → File → Run SQL file  կամ  mysql -u root < sql/schema.sql
SET NAMES utf8mb4;

DROP DATABASE IF EXISTS ani_books;

CREATE DATABASE ani_books
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE ani_books;

-- Ժանրը՝ առանձին աղյուսակ․ «պատմավեպ»-ը գրվում է մեկ անգամ, ոչ ամեն գրքում
CREATE TABLE genres (
    id    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name  VARCHAR(40) NOT NULL UNIQUE,
    slug  VARCHAR(40) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE books (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    genre_id    INT UNSIGNED NULL,                      -- NULL՝ եթե ժանրը ջնջվի
    title       VARCHAR(200) NOT NULL,
    slug        VARCHAR(200) NOT NULL UNIQUE,
    author      VARCHAR(100) NOT NULL,
    year        SMALLINT     NULL,
    pages       SMALLINT UNSIGNED NOT NULL,
    read_pages  SMALLINT UNSIGNED NOT NULL DEFAULT 0,   -- սեսիաների գումարը (Դաս 11-ում՝ տրանզակցիայով)
    rating      TINYINT UNSIGNED NOT NULL DEFAULT 0,
    color       CHAR(7)      NOT NULL DEFAULT '#003459',
    link        VARCHAR(255) NULL,
    cover       VARCHAR(255) NULL,
    is_active   TINYINT(1)   NOT NULL DEFAULT 1,
    created_at  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_books_genre
        FOREIGN KEY (genre_id) REFERENCES genres(id)
        ON DELETE SET NULL,                             -- ժանրը ջնջվեց → գիրքը մնում է առանց ժանրի

    INDEX idx_books_active (is_active)                  -- գրեթե ամեն հարցում ունի WHERE is_active = 1
) ENGINE=InnoDB;

-- Ընթերցանության օրերը․ մեկ գիրք → շատ սեսիաներ (ինչպես պատվեր → տողեր)
CREATE TABLE reading_sessions (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    book_id     INT UNSIGNED NOT NULL,
    pages       SMALLINT UNSIGNED NOT NULL,
    read_on     DATE         NOT NULL,
    note        VARCHAR(255) NULL,
    created_at  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_sessions_book
        FOREIGN KEY (book_id) REFERENCES books(id)
        ON DELETE RESTRICT,                             -- պատմությամբ գիրքը չի ջնջվում, միայն թաքցվում է

    INDEX idx_sessions_date (read_on)
) ENGINE=InnoDB;
