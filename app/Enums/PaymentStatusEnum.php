<?php

namespace App\Enums;

enum PaymentStatusEnum: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case PARTIAL = 'partial';
    case DUE = 'due'; // For credit sales/purchases past due date

    public function label(): string
    {
        return match ($this) {
            self::PENDING => __('Pending'),
            self::PAID => __('Paid'),
            self::PARTIAL => __('Partial'),
            self::DUE => __('Due'),
        };
    }
}
