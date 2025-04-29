<?php

namespace App\Enums;

enum StockTransferStatusEnum: string
{
    case PENDING = 'pending';   // Transfer initiated, not yet sent/received
    case SENT = 'sent';       // Items dispatched from source warehouse
    case COMPLETED = 'completed'; // Items received at destination warehouse
    case CANCELLED = 'cancelled'; // Transfer cancelled

    public function label(): string
    {
        return match ($this) {
            self::PENDING => __('Pending'),
            self::SENT => __('Sent'),
            self::COMPLETED => __('Completed'),
            self::CANCELLED => __('Cancelled'),
        };
    }
}
