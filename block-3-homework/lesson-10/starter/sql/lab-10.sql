-- ⚠️ ՓՈՐՁՆԱԿԱՆ աղյուսակ՝ միայն lab-injection.php-ի համար — PHP Դաս 10, տնային 3
-- Գաղտնաբառերը բաց տեքստով են ԴԻՏԱՎՈՐՅԱԼ․ իսկական հաշիվները՝ Դաս 16-ում, password_hash()-ով
-- Փորձից հետո՝ DROP TABLE lab_members;
SET NAMES utf8mb4;
USE ani_books;

DROP TABLE IF EXISTS lab_members;

CREATE TABLE lab_members (
    id        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email     VARCHAR(100) NOT NULL UNIQUE,
    password  VARCHAR(100) NOT NULL
);

INSERT INTO lab_members (email, password) VALUES
('ani@example.com', 'sevan-2026'),
('aram@example.com', 'gikor-1907');
