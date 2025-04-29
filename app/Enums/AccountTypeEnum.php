<?php

namespace App\Enums;

enum AccountTypeEnum: string
{
    case ASSET = 'asset';
    case LIABILITY = 'liability';
    case EQUITY = 'equity';
    case REVENUE = 'revenue';
    case EXPENSE = 'expense';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::ASSET => __('Asset'),
            self::LIABILITY => __('Liability'),
            self::EQUITY => __('Equity'),
            self::REVENUE => __('Revenue'),
            self::EXPENSE => __('Expense'),
            self::OTHER => __('Other')
        };
    }
}
