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
    public function description(): string
    {
        return match ($this) {
            self::YES => __('The answer is affirmative.'),
            self::NO => __('The answer is negative.'),
        };
    }
    public function icon(): string
    {
        return match ($this) {
            self::YES => 'check-circle',
            self::NO => 'x-circle',
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
