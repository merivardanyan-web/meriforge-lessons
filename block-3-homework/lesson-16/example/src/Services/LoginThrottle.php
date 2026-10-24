<?php
declare(strict_types=1);

namespace App\Services;

/**
 * Brute force-ի դեմ՝ սեսիայում — PHP Դաս 16
 * ⚠️ Ուսումնական․ cookie-ները ջնջելով շրջանցվում է (տնային 11)։ Իրականում հաշվում են ըստ IP-ի
 */
class LoginThrottle
{
    public function __construct(
        private readonly string $key = 'login_attempts',
        private readonly int $max = 5,
        private readonly int $lockSeconds = 900,
    ) {}

    public function tooManyAttempts(): bool
    {
        $data = $_SESSION[$this->key] ?? ['count' => 0, 'until' => 0];

        return $data['count'] >= $this->max && $data['until'] > time();
    }

    public function hit(): void
    {
        $data = $_SESSION[$this->key] ?? ['count' => 0, 'until' => 0];

        if ($data['until'] < time()) {
            $data['count'] = 0;
        }

        $data['count']++;
        $data['until'] = time() + $this->lockSeconds;

        $_SESSION[$this->key] = $data;
    }

    public function clear(): void
    {
        unset($_SESSION[$this->key]);
    }
}
