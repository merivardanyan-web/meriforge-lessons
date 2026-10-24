<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use PDO;

class UserRepository
{
    public function __construct(private PDO $pdo) {}

    public function find(int $id): ?User
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row === false ? null : User::fromRow($row);
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(['email' => mb_strtolower(trim($email))]);
        $row = $stmt->fetch();

        return $row === false ? null : User::fromRow($row);
    }

    /** role-ը ՉԵՆՔ ընդունում․ բազան կդնի DEFAULT 'member' (տնային 10) ⭐ */
    public function create(string $email, string $passwordHash, string $name): int
    {
        $stmt = $this->pdo->prepare('INSERT INTO users (email, password_hash, name) VALUES (:email, :hash, :name)');
        $stmt->execute(['email' => $email, 'hash' => $passwordHash, 'name' => $name]);

        return (int) $this->pdo->lastInsertId();
    }

    public function updatePassword(int $id, string $hash): void
    {
        $stmt = $this->pdo->prepare('UPDATE users SET password_hash = :hash WHERE id = :id');
        $stmt->execute(['hash' => $hash, 'id' => $id]);
    }
}
