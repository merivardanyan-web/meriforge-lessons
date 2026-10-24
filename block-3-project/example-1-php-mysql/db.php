<?php
declare(strict_types=1);

/** Մեկ միացում մեկ հարցումի ընթացքում — PHP Դաս 10 */
function db(): PDO
{
    static $pdo = null;

    if ($pdo !== null) {
        return $pdo;
    }

    $c = config('db');
    $dsn = "mysql:host={$c['host']};dbname={$c['name']};charset={$c['charset']}";

    try {
        $pdo = new PDO($dsn, $c['user'], $c['password'], [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    } catch (PDOException $e) {
        // ⚠️ Օգտագործողին երբեք $e->getMessage() — այնտեղ կարող է լինել գաղտնաբառը
        error_log('DB connection failed: ' . $e->getMessage());
        http_response_code(500);
        exit('Տեխնիկական խնդիր։ Փորձեք ավելի ուշ։');
    }

    return $pdo;
}
