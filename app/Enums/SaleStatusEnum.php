<?php

namespace App\Enums;

enum SaleStatusEnum: string
{
    case PENDING = 'pending'; // Order placed but not finalized/shipped
    case COMPLETED = 'completed';
    case SHIPPED = 'shipped'; // Optional, if delivery is involved
    case RETURNED = 'returned'; // If fully returned

    public function label(): string
    {
        return match ($this) {
            self::PENDING => __('Pending'),
            self::COMPLETED => __('Completed'),
            self::SHIPPED => __('Shipped'),
            self::RETURNED => __('Returned'),
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::PENDING => __('The sale has been initiated but not yet finalized or shipped.'),
            self::COMPLETED => __('The sale has been completed and payment has been received.'),
            self::SHIPPED => __('The sale has been shipped to the customer.'),
            self::RETURNED => __('The sale has been fully returned by the customer.'),
        };
    }
}
