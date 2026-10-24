<?php
declare(strict_types=1);

namespace App\Models;

/** Օգտագործող — PHP Դաս 16 */
class User
{
    public function __construct(
        public readonly int $id,
        public readonly string $email,
        public readonly string $name,
        public readonly string $role,
        public readonly string $passwordHash,
    ) {}

    public static function fromRow(array $row): self
    {
        return new self(
            id:           (int) $row['id'],
            email:        (string) $row['email'],
            name:         (string) $row['name'],
            role:         (string) $row['role'],
            passwordHash: (string) $row['password_hash'],
        );
    }

    public function isStaff(): bool
    {
        return in_array($this->role, ['admin', 'manager'], true);
    }
}
