<?php
declare(strict_types=1);

namespace App\Services;

// Րոպեներ → «3 ժ 20 ր» — PHP Դաս 13, տնային 9
// Կոմպոզիցիա․ Book-ը ՕԳՏԱԳՈՐԾՈՒՄ է ձևաչափիչը, չի ժառանգում այն։
// Նույն դասը պետք կգա ցուցակի ընդհանուր տևողության համար
class DurationFormatter
{
    public function format(int $minutes): string
    {
        if ($minutes <= 0) {
            return '0 ր';
        }

        $hours = (int) ($minutes / 60);        // 200 / 60 = 3.33 → 3
        $rest  = $minutes % 60;

        if ($hours === 0) {
            return "{$rest} ր";
        }

        return $rest === 0 ? "{$hours} ժ" : "{$hours} ժ {$rest} ր";
    }
}
