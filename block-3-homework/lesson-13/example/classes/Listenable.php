<?php
declare(strict_types=1);

// Ինչ կարելի է լսել — PHP Դաս 13
interface Listenable
{
    public function durationMinutes(): int;

    public function narrator(): ?string;
}
