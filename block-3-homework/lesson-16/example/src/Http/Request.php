<?php
declare(strict_types=1);

namespace App\Http;

/** $_GET, $_POST, $_FILES, $_SERVER՝ մեկ օբյեկտում — PHP Դաս 15 */
class Request
{
    public function __construct(
        public readonly string $method,
        public readonly string $path,
        private readonly array $query,
        private readonly array $body,
        private readonly array $files = [],
        private readonly array $server = [],
    ) {}

    public static function fromGlobals(): self
    {
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

        return new self(
            strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET'),
            '/' . trim($path, '/'),                   // /book/samvel/ → /book/samvel
            $_GET,
            $_POST,
            $_FILES,
            $_SERVER,
        );
    }

    public function query(string $key, string $default = ''): string
    {
        $value = $this->query[$key] ?? $default;

        return is_string($value) ? trim($value) : $default;     // ?q[]=x-ը զանգված է, ոչ տող
    }

    /** Ամբողջ ?query-ն՝ ֆիլտրերի համար */
    public function allQuery(): array
    {
        return $this->query;
    }

    public function input(string $key, string $default = ''): string
    {
        $value = $this->body[$key] ?? $default;

        return is_scalar($value) ? trim((string) $value) : $default;
    }

    /** Ամբողջ մարմինը՝ վալիդացիայի համար */
    public function all(): array
    {
        return $this->body;
    }

    public function int(string $key, int $default = 0): int
    {
        $value = filter_var($this->body[$key] ?? $this->query[$key] ?? null, FILTER_VALIDATE_INT);

        return $value === false ? $default : $value;
    }

    /** Զանգված ֆորմայից՝ ids[] */
    public function array(string $key): array
    {
        $value = $this->body[$key] ?? [];

        return is_array($value) ? $value : [];
    }

    public function file(string $key): ?array
    {
        $file = $this->files[$key] ?? null;

        return is_array($file) && ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE ? $file : null;
    }

    public function uri(): string
    {
        return (string) ($this->server['REQUEST_URI'] ?? $this->path);
    }
}
