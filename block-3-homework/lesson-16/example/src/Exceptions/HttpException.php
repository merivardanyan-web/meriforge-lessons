<?php
declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

/** Սպասված սխալ՝ HTTP կոդով (404, 405...) — PHP Դաս 15 */
class HttpException extends RuntimeException
{
    public function __construct(string $message, public readonly int $status = 500)
    {
        parent::__construct($message);
    }
}
