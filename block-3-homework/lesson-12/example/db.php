<?php
declare(strict_types=1);

// Մեկ միացում բազային — PHP Դաս 10
function db(): PDO
{
    static $pdo = null;      // մեկ միացում մեկ հարցումի ընթացքում ⭐

    if ($pdo !== null) {
        return $pdo;
    }

    $config = require __DIR__ . '/config.php';
    $c = $config['db'];

    $dsn = "mysql:host={$c['host']};dbname={$c['name']};charset={$c['charset']}";

    try {
        $pdo = new PDO($dsn, $c['user'], $c['password'], [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,   // սխալը՝ բացառություն, ոչ լուռ false
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,                    // իսկական prepared statements
        ]);
    } catch (PDOException $e) {
        // ⚠️ Օգտագործողին երբեք չցուցադրել $e->getMessage()․ այնտեղ կա հոսթը և օգտանունը
        error_log('DB connection failed: ' . $e->getMessage());
        http_response_code(500);
        exit('Տեխնիկական խնդիր։ Փորձիր ավելի ուշ։');
    }

    return $pdo;
}
