<?php

namespace App\Enums;

enum UserTitles: string
{
    case Mr = 'Mr';
    case Ms = 'Ms';
    case Mrs = 'Mrs';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
