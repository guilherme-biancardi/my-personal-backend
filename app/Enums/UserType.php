<?php
namespace App\Enums;

enum UserType: string
{
    case OWNER = 'owner';
    case CLIENT = 'client';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
