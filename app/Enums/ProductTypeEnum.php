<?php

namespace App\Enums;

enum ProductTypeEnum: string
{
    case STANDARD = 'standard';
    case SERVICE = 'service';
    case COMBO = 'combo'; // For product bundles

    public function label(): string
    {
        return match ($this) {
            self::STANDARD => __('Standard'),
            self::SERVICE => __('Service'),
            self::COMBO => __('Combo'),
        };
    }
    public function description(): string
    {
        return match ($this) {
            self::STANDARD => __('A standard product that is sold individually.'),
            self::SERVICE => __('A service that is provided to customers.'),
            self::COMBO => __('A combination of multiple products sold as a bundle.'),
        };
    }
}
