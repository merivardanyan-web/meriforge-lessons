-- Ակումբի անդամներ — PHP Դաս 16
-- Կատարիր schema.sql, seed.sql, upgrade-13.sql-ից հետո
SET NAMES utf8mb4;
USE ani_books;

DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email         VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,            -- password_hash()-ի արդյունքը, ՈՉ գաղտնաբառը
    name          VARCHAR(60)  NOT NULL,
    role          VARCHAR(10)  NOT NULL DEFAULT 'member',   -- admin · editor · member
    created_at    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Գաղտնաբառերը (միայն տեղական փորձի համար)․
--   ani@example.com   Ani-12345    admin   — ամեն ինչ
--   lilit@example.com Lilit-12345  editor  — ավելացնում և խմբագրում է, բայց չի թաքցնում
--   aram@example.com  Aram-12345   member  — ակումբի անդամ, ադմին չի մտնում
INSERT INTO users (email, password_hash, name, role) VALUES
('ani@example.com',   '$2y$10$CxxIjyzVQ5vj6wPgcrUDhe090rCqrbz917n24TaUdoNh4NGnQcVvG', 'Անի',   'admin'),
('lilit@example.com', '$2y$10$YYGrIsLVVptLFw/s5fZVn.nEOXoUYZzeYIXEHWy0sSkTEx2mGyYZ.', 'Լիլիթ', 'editor'),
('aram@example.com',  '$2y$10$QlOx/ScNNUZs45QN84oCUOdc/hXG.HQ09kE9U5l5HRPF0OV1GjFV6', 'Արամ',  'member');
