<?php

namespace App\Enums;

enum InventoryStatusEnum: string
{
    case IN_STOCK = 'in_stock';
    case OUT_OF_STOCK = 'out_of_stock';
    case LOW_STOCK = 'low_stock';
    case BACKORDERED = 'backordered';
    case DISCONTINUED = 'discontinued';

    public function label(): string
    {
        return match ($this) {
            self::IN_STOCK => __('In Stock'),
            self::OUT_OF_STOCK => __('Out of Stock'),
            self::LOW_STOCK => __('Low Stock'),
            self::BACKORDERED => __('Backordered'),
            self::DISCONTINUED => __('Discontinued'),
        };
    }
    public function description(): string
    {
        return match ($this) {
            self::IN_STOCK => __('The item is available for sale and can be shipped immediately.'),
            self::OUT_OF_STOCK => __('The item is currently not available for sale.'),
            self::LOW_STOCK => __('The item is running low on inventory and may need to be reordered soon.'),
            self::BACKORDERED => __('The item is not currently in stock but can be ordered for future delivery.'),
            self::DISCONTINUED => __('The item is no longer being produced or sold.'),
        };
    }
}
