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
    public function description(): string
    {
        return match ($this) {
            self::PENDING => __('The payment is pending and has not yet been made.'),
            self::PAID => __('The payment has been completed.'),
            self::PARTIAL => __('A partial payment has been made.'),
            self::DUE => __('The payment is due and has not been made by the due date.'),
        };
    }
}
