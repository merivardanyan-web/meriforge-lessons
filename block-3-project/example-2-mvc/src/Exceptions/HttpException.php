<?php
declare(strict_types=1);

namespace App\Exceptions;

/** Սպասված սխալ՝ HTTP կոդով (404, 403, 419...) — PHP Դաս 15 */
class HttpException extends \RuntimeException
{
    public function __construct(string $message, public readonly int $status = 500)
    {
        parent::__construct($message);
    }
}
