<?php
declare(strict_types=1);

namespace App\Http;

/** Կոնտրոլերը ոչինչ չի echo անում․ վերադարձնում է Response — PHP Դաս 15 */
class Response
{
    public function __construct(
        private readonly string $body = '',
        private readonly int $status = 200,
        private readonly array $headers = [],
    ) {}

    public static function html(string $body, int $status = 200): self
    {
        return new self($body, $status, ['Content-Type' => 'text/html; charset=utf-8']);
    }

    public static function redirect(string $to): self
    {
        return new self('', 303, ['Location' => $to]);    // PRG — Դաս 06
    }

    public function send(): void
    {
        http_response_code($this->status);

        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }

        echo $this->body;
    }
}
