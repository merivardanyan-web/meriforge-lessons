<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;

/** Մուտք, ելք, ընթացիկ անդամ — PHP Դաս 16 */
class Auth
{
    private ?User $cached = null;

    public function __construct(private readonly UserRepository $users) {}

    public function attempt(string $email, string $password): bool
    {
        $user = $this->users->findByEmail($email);

        // ⭐ Ստուգում ենք ՄԻՇՏ, նույնիսկ երբ անդամը չկա․ թե չէ պատասխանի արագությունը
        // կհուշեր, որ այդ հասցեն գոյություն չունի (timing attack, տնային 5)
        $hash = $user?->passwordHash ?? '$2y$10$' . str_repeat('x', 53);

        if (!password_verify($password, $hash) || $user === null) {
            return false;
        }

        if (password_needs_rehash($hash, PASSWORD_DEFAULT)) {
            $this->users->updatePassword($user->id, password_hash($password, PASSWORD_DEFAULT));
        }

        $this->login($user);

        return true;
    }

    public function login(User $user): void
    {
        session_regenerate_id(true);           // ⭐ նոր PHPSESSID՝ session fixation-ի դեմ (տնային 6)

        $_SESSION['user_id'] = $user->id;
        $this->cached = $user;
    }

    public function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }

        session_destroy();
        $this->cached = null;
    }

    public function user(): ?User
    {
        if ($this->cached !== null) {
            return $this->cached;
        }

        $id = $_SESSION['user_id'] ?? null;

        return $this->cached = $id ? $this->users->find((int) $id) : null;
    }

    public function check(): bool
    {
        return $this->user() !== null;
    }

    public function isStaff(): bool
    {
        return $this->user()?->isStaff() ?? false;
    }

    public function isAdmin(): bool
    {
        return $this->user()?->role === 'admin';
    }
}
