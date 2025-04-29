<?php

namespace App\Enums;

enum MenuType: string
{
    case LINK = 'link';
    case DROPDOWN = 'dropdown';
    case HEADER = 'header';

    public function label(): string
    {
        return match ($this) {
            self::LINK => 'Link',
            self::DROPDOWN => 'Dropdown',
            self::HEADER => 'Header',
        };
    }
    public function icon(): string
    {
        return match ($this) {
            self::LINK => 'link',
            self::DROPDOWN => 'chevron-down',
            self::HEADER => 'file-earmark-text',
        };
    }
}
