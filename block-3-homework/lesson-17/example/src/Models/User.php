<?php
declare(strict_types=1);

namespace App\Models;

/** Ակումբի անդամ — PHP Դաս 16 */
class User
{
    public const ROLES = ['admin', 'editor', 'member'];

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
            id: (int) $row['id'],
            email: $row['email'],
            name: $row['name'],
            role: $row['role'],
            passwordHash: $row['password_hash'],
        );
    }

    /** Ադմին վահանակ մտնում են admin-ը և editor-ը */
    public function isStaff(): bool
    {
        return in_array($this->role, ['admin', 'editor'], true);
    }
}
