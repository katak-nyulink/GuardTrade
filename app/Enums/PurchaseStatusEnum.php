<?php

namespace App\Enums;

enum PurchaseStatusEnum: string
{
    case PENDING = 'pending'; // Order placed but not received
    case ORDERED = 'ordered'; // Optional: Confirmed with supplier
    case RECEIVED = 'received'; // All items received
    case PARTIAL_RECEIVED = 'partial_received'; // Some items received
    case RETURNED = 'returned'; // If fully returned

    public function label(): string
    {
        return match ($this) {
            self::PENDING => __('Pending'),
            self::ORDERED => __('Ordered'),
            self::RECEIVED => __('Received'),
            self::PARTIAL_RECEIVED => __('Partial Received'),
            self::RETURNED => __('Returned'),
        };
    }
}
