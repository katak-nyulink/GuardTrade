<?php

namespace App\Enums;

enum InventoryAccountingMethod: string
{
    case FIFO = 'fifo';
    case LIFO = 'lifo';
    case WAC = 'wac';
    case SI = 'si';

    public function label(): string
    {
        return match ($this) {
            self::FIFO => __('First In First Out'),
            self::LIFO => __('Last In First Out'),
            self::WAC => __('Weighted Average Cost'),
            self::SI => __('Specific Identification'),
        };
    }
    public function description(): string
    {
        return match ($this) {
            self::FIFO => __('The oldest inventory items are sold first.'),
            self::LIFO => __('The most recent inventory items are sold first.'),
            self::WAC => __('Inventory cost is averaged over all items.'),
            self::SI => __('Each item is tracked individually.'),
        };
    }

    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'label' => $this->label(),
            'description' => $this->description(),
        ];
    }

    public static function getAllCases(): array
    {
        $cases = [];
        foreach (self::cases() as $case) {
            $cases[$case->value] = $case->label();
        }
        return $cases;
    }

    public static function options(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }
}
