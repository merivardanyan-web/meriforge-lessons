<?php
declare(strict_types=1);

namespace App\Support;

use PDO;              // ⭐ առանց սրա PHP-ն կփնտրեր App\Support\PDO (տնային 2)
use PDOException;

// db()-ն՝ դասով — PHP Դաս 10, 14
class Database
{
    public static function connect(array $config): PDO
    {
        $dsn = "mysql:host={$config['host']};dbname={$config['name']};charset=utf8mb4";

        try {
            return new PDO($dsn, $config['user'], $config['password'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            error_log('DB connection failed: ' . $e->getMessage());
            http_response_code(500);
            exit('Տեխնիկական խնդիր։ Փորձիր ավելի ուշ։');
        }
    }
}
