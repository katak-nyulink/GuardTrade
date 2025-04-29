<?php

namespace Database\Seeders;

use App\Enums\AccountTypeEnum;
use App\Models\Account;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // --- Assets ---
        $assets = Account::firstOrCreate(['name' => 'Assets', 'type' => AccountTypeEnum::ASSET, 'is_header' => true]);
        $currentAssets = Account::firstOrCreate(['name' => 'Current Assets', 'type' => AccountTypeEnum::ASSET, 'parent_id' => $assets->id, 'is_header' => true]);
        Account::firstOrCreate(['name' => 'Cash on Hand', 'code' => '1010', 'type' => AccountTypeEnum::ASSET, 'parent_id' => $currentAssets->id]);
        Account::firstOrCreate(['name' => 'Bank Accounts', 'code' => '1020', 'type' => AccountTypeEnum::ASSET, 'parent_id' => $currentAssets->id]);
        Account::firstOrCreate(['name' => 'Accounts Receivable', 'code' => '1200', 'type' => AccountTypeEnum::ASSET, 'parent_id' => $currentAssets->id]);
        Account::firstOrCreate(['name' => 'Inventory', 'code' => '1400', 'type' => AccountTypeEnum::ASSET, 'parent_id' => $currentAssets->id]);
        $fixedAssets = Account::firstOrCreate(['name' => 'Fixed Assets', 'type' => AccountTypeEnum::ASSET, 'parent_id' => $assets->id, 'is_header' => true]);
        Account::firstOrCreate(['name' => 'Equipment', 'code' => '1500', 'type' => AccountTypeEnum::ASSET, 'parent_id' => $fixedAssets->id]);

        // --- Liabilities ---
        $liabilities = Account::firstOrCreate(['name' => 'Liabilities', 'type' => AccountTypeEnum::LIABILITY, 'is_header' => true]);
        $currentLiabilities = Account::firstOrCreate(['name' => 'Current Liabilities', 'type' => AccountTypeEnum::LIABILITY, 'parent_id' => $liabilities->id, 'is_header' => true]);
        Account::firstOrCreate(['name' => 'Accounts Payable', 'code' => '2010', 'type' => AccountTypeEnum::LIABILITY, 'parent_id' => $currentLiabilities->id]);
        Account::firstOrCreate(['name' => 'Sales Tax Payable', 'code' => '2200', 'type' => AccountTypeEnum::LIABILITY, 'parent_id' => $currentLiabilities->id]);

        // --- Equity ---
        $equity = Account::firstOrCreate(['name' => 'Equity', 'type' => AccountTypeEnum::EQUITY, 'is_header' => true]);
        Account::firstOrCreate(['name' => 'Owner\'s Equity', 'code' => '3010', 'type' => AccountTypeEnum::EQUITY, 'parent_id' => $equity->id]);
        Account::firstOrCreate(['name' => 'Retained Earnings', 'code' => '3200', 'type' => AccountTypeEnum::EQUITY, 'parent_id' => $equity->id]);

        // --- Revenue ---
        $revenue = Account::firstOrCreate(['name' => 'Revenue', 'type' => AccountTypeEnum::REVENUE, 'is_header' => true]);
        Account::firstOrCreate(['name' => 'Sales Revenue', 'code' => '4010', 'type' => AccountTypeEnum::REVENUE, 'parent_id' => $revenue->id]);
        Account::firstOrCreate(['name' => 'Shipping Revenue', 'code' => '4020', 'type' => AccountTypeEnum::REVENUE, 'parent_id' => $revenue->id]);
        Account::firstOrCreate(['name' => 'Sales Discounts', 'code' => '4030', 'type' => AccountTypeEnum::REVENUE, 'parent_id' => $revenue->id]); // Contra-revenue

        // --- Expenses ---
        $expenses = Account::firstOrCreate(['name' => 'Expenses', 'type' => AccountTypeEnum::EXPENSE, 'is_header' => true]);
        $cogs = Account::firstOrCreate(['name' => 'Cost of Goods Sold', 'code' => '5000', 'type' => AccountTypeEnum::EXPENSE, 'parent_id' => $expenses->id, 'is_header' => true]);
        Account::firstOrCreate(['name' => 'Cost of Goods Sold', 'code' => '5010', 'type' => AccountTypeEnum::EXPENSE, 'parent_id' => $cogs->id]); // Specific COGS account
        $operatingExpenses = Account::firstOrCreate(['name' => 'Operating Expenses', 'type' => AccountTypeEnum::EXPENSE, 'parent_id' => $expenses->id, 'is_header' => true]);
        Account::firstOrCreate(['name' => 'Rent Expense', 'code' => '6010', 'type' => AccountTypeEnum::EXPENSE, 'parent_id' => $operatingExpenses->id]);
        Account::firstOrCreate(['name' => 'Utilities Expense', 'code' => '6020', 'type' => AccountTypeEnum::EXPENSE, 'parent_id' => $operatingExpenses->id]);
        Account::firstOrCreate(['name' => 'Bank Fees', 'code' => '6100', 'type' => AccountTypeEnum::EXPENSE, 'parent_id' => $operatingExpenses->id]);
        Account::firstOrCreate(['name' => 'Purchase Discounts', 'code' => '6200', 'type' => AccountTypeEnum::EXPENSE, 'parent_id' => $operatingExpenses->id]); // Contra-expense
    }
}
