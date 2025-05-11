<?php

namespace App\Enums;

enum UserAdmin: int
{
    case USER = 1;
    case ADMIN = 2;
    case QUEST = 3;
    public function label(): string
    {
        return match ($this) {
            self::USER => __('User'),
            self::ADMIN => __('Admin'),
            self::QUEST => __('Quest'),
        };
    }
    public function description(): string
    {
        return match ($this) {
            self::USER => __('A regular user with standard permissions.'),
            self::ADMIN => __('An administrator with elevated permissions.'),
            self::QUEST => __('A guest user with limited access.'),
        };
    }
    public function icon(): string
    {
        return match ($this) {
            self::USER => 'person',
            self::ADMIN => 'shield-lock',
            self::QUEST => 'person-badge',
        };
    }
}
