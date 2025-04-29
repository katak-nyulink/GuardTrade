<?php

namespace App\Enums;

enum YesNo: int
{
    case YES = 1;
    case NO = 0;
    public function label(): string
    {
        return match ($this) {
            self::YES => __('Yes'),
            self::NO => __('No'),
        };
    }
    public static function values(): array
    {
        return [
            self::YES,
            self::NO,
        ];
    }
}
