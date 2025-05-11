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
    public function description(): string
    {
        return match ($this) {
            self::LINK => __('A single link to a page or resource.'),
            self::DROPDOWN => __('A menu that expands to show more options.'),
            self::HEADER => __('A header for grouping related links.'),
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
