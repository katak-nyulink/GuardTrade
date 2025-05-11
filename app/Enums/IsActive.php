<?php

namespace App\Enums;

enum IsActive: int
{
    case ACTIVE = 1;
    case INACTIVE = 0;
    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => __('Active'),
            self::INACTIVE => __('Inactive'),
        };
    }
    public function description(): string
    {
        return match ($this) {
            self::ACTIVE => __('The item is currently active and available for use.'),
            self::INACTIVE => __('The item is currently inactive and not available for use.'),
        };
    }
    public static function values(): array
    {
        return [
            self::ACTIVE,
            self::INACTIVE,
        ];
    }

    public function color(): string
    {
        return match ($this) {
            self::ACTIVE => 'green',
            self::INACTIVE => 'amber',
        };
    }
}
