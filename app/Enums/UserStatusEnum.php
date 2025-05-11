<?php

namespace App\Enums;

enum UserStatusEnum: int
{
    case ACTIVE = 1;
    case INACTIVE = 2;
    case SUSPENDED = 3;
    case BANNED = 4;

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => __('Active'),
            self::INACTIVE => __('Inactive'),
            self::SUSPENDED => __('Suspended'),
            self::BANNED => __('Banned'),
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::ACTIVE => __('The user is active and can access the system.'),
            self::INACTIVE => __('The user is inactive and cannot access the system.'),
            self::SUSPENDED => __('The user is suspended and cannot access the system temporarily.'),
            self::BANNED => __('The user is banned and cannot access the system.'),
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::ACTIVE => 'check-circle',
            self::INACTIVE => 'pause-circle',
            self::SUSPENDED => 'exclamation-triangle',
            self::BANNED => 'x-circle',
        };
    }
}
