<?php
declare(strict_types=1);

namespace App\Http;

use App\Exceptions\HttpException;

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
        $body = $_POST;

        // JSON մարմին — PHP Դաս 17։ fetch(..., {body: JSON.stringify(...)})-ի դեպքում $_POST-ը ԴԱՏԱՐԿ է ⭐ (տնային 4)
        if (str_contains($_SERVER['CONTENT_TYPE'] ?? '', 'application/json')) {
            $raw = file_get_contents('php://input');

            if ($raw !== '' && $raw !== false) {
                $decoded = json_decode($raw, true);

                if (!is_array($decoded)) {
                    throw new HttpException('Սխալ JSON', 400);
                }

                $body = $decoded;
            }
        }

        return new self(
            strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET'),
            '/' . trim($path, '/'),                   // /book/samvel/ → /book/samvel
            $_GET,
            $body,
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

    /** header('X-CSRF-Token') → $_SERVER['HTTP_X_CSRF_TOKEN'] */
    public function header(string $name): string
    {
        $key = 'HTTP_' . strtoupper(str_replace('-', '_', $name));

        return trim((string) ($this->server[$key] ?? ''));
    }

    public function expectsJson(): bool
    {
        return str_starts_with($this->path, '/api/') || str_contains($this->header('Accept'), 'application/json');
    }

    public function uri(): string
    {
        return (string) ($this->server['REQUEST_URI'] ?? $this->path);
    }
}
