<?php
declare(strict_types=1);

namespace App\Exceptions;

class NotFoundException extends HttpException
{
    public function __construct(string $message = 'Էջը չի գտնվել')
    {
        parent::__construct($message, 404);
    }
}
