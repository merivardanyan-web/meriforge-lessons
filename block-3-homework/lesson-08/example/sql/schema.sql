-- Անիի ակումբը · բազա — PHP Դաս 08
-- HeidiSQL → File → Run SQL file  կամ  mysql -u root < sql/schema.sql
SET NAMES utf8mb4;   -- ⚠️ առանց սրա mysql տերմինալը հայերենը կպահի «Õ†...» տեսքով

DROP DATABASE IF EXISTS ani_books;

CREATE DATABASE ani_books
    CHARACTER SET utf8mb4             -- էմոջի և հայերեն
    COLLATE utf8mb4_unicode_ci;

USE ani_books;

CREATE TABLE books (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(200) NOT NULL,
    slug        VARCHAR(200) NOT NULL UNIQUE,          -- հասցեի համար՝ book/samvel (Դաս 15)
    author      VARCHAR(100) NOT NULL,
    year        SMALLINT     NULL,                      -- հայտնի չէ՝ NULL, ոչ 0
    pages       SMALLINT UNSIGNED NOT NULL,
    read_pages  SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    genre       VARCHAR(40)  NOT NULL,                  -- Դաս 09-ում՝ առանձին աղյուսակ
    rating      TINYINT UNSIGNED NOT NULL DEFAULT 0,    -- 0 = դեռ չեմ գնահատել
    color       CHAR(7)      NOT NULL DEFAULT '#003459',
    link        VARCHAR(255) NULL,
    cover       VARCHAR(255) NULL,                      -- Դաս 07-ի վերբեռնումը՝ Դաս 11-ից
    is_active   TINYINT(1)   NOT NULL DEFAULT 1,        -- թաքցնել, ոչ ջնջել
    created_at  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
);
