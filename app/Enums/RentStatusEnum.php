<?php

namespace App\Enums;

enum RentStatusEnum: string
{
    case AVAILABLE = 'available';
    case RENTED = 'rented';
    case RETURNED = 'returned';

    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
