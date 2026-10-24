<?php
declare(strict_types=1);

namespace App\Http;

use App\Exceptions\HttpException;

class Request
{
    public function __construct(
        public readonly string $method,
        public readonly string $path,
        private readonly array $query,
        private readonly array $body,
        private readonly array $server = [],
        private readonly array $files = [],
    ) {}

    public static function fromGlobals(): self
    {
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $body = $_POST;

        // JSON մարմին — PHP Դաս 17։ $_POST-ը JSON-ի դեպքում դատարկ է ⭐
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
            '/' . trim($path, '/'),
            $_GET,
            $body,
            $_SERVER,
            $_FILES,
        );
    }

    public function query(string $key, string $default = ''): string
    {
        $value = $this->query[$key] ?? $default;

        return is_string($value) ? trim($value) : $default;     // ?q[]=x-ը զանգված է, ոչ տող
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

    public function has(string $key): bool
    {
        return isset($this->body[$key]);
    }

    public function int(string $key, int $default = 0): int
    {
        $value = filter_var($this->body[$key] ?? $this->query[$key] ?? null, FILTER_VALIDATE_INT);

        return $value === false ? $default : $value;
    }

    /** Ոչ բացասական թիվ URL-ից կամ null (դատարկ դաշտը ֆիլտր չէ) */
    public function queryInt(string $key): ?int
    {
        $value = filter_var($this->query[$key] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);

        return $value === false ? null : $value;
    }

    /** Ցուցակից մեկը — սպիտակ ցուցակ, PHP Դաս 6 ⭐ */
    public function oneOf(string $key, array $allowed, string $default): string
    {
        $value = $this->query($key);

        return in_array($value, $allowed, true) ? $value : $default;
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

    /** header('X-CSRF-Token') → $_SERVER['HTTP_X_CSRF_TOKEN'] — PHP Դաս 17 */
    public function header(string $name): string
    {
        $key = 'HTTP_' . strtoupper(str_replace('-', '_', $name));

        return trim((string) ($this->server[$key] ?? ''));
    }

    public function expectsJson(): bool
    {
        return str_starts_with($this->path, '/api/')
            || str_contains($this->header('Accept'), 'application/json');
    }

    public function uri(): string
    {
        return (string) ($this->server['REQUEST_URI'] ?? $this->path);
    }
}
