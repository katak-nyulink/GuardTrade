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
    public function description(): string
    {
        return match ($this) {
            self::ASSET => __('Assets are resources owned by a business.'),
            self::LIABILITY => __('Liabilities are obligations of a business.'),
            self::EQUITY => __('Equity represents the ownership interest in a business.'),
            self::REVENUE => __('Revenue is the income generated from normal business operations.'),
            self::EXPENSE => __('Expenses are the costs incurred in the process of earning revenue.'),
            self::OTHER => __('Other accounts that do not fit into the above categories.')
        };
    }
}
