<?php
declare(strict_types=1);

namespace App\Exceptions;

/** 422 + դաշտերի սխալները — PHP Դաս 17 */
class ValidationException extends HttpException
{
    public function __construct(
        public readonly array $errors,
        string $message = 'Տվյալները վավեր չեն',
    ) {
        parent::__construct($message, 422);
    }
}
