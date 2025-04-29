<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Setting;
use App\Models\Warehouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultWarehouse = Warehouse::where('is_default', true)->first();

        $settings = [
            ['key' => 'app_name', 'value' => 'Laravel POS', 'group' => 'general'],
            ['key' => 'app_logo', 'value' => null, 'group' => 'general'], // Store path to logo if uploaded
            ['key' => 'default_currency_symbol', 'value' => '$', 'group' => 'localization'],
            ['key' => 'default_currency_code', 'value' => 'USD', 'group' => 'localization'],
            ['key' => 'default_date_format', 'value' => 'Y-m-d', 'group' => 'localization'],
            ['key' => 'default_warehouse_id', 'value' => $defaultWarehouse?->id, 'group' => 'defaults'],
            ['key' => 'default_customer_id', 'value' => 1, 'group' => 'defaults'], // Assuming Walk-in Customer is ID 1
            ['key' => 'default_tax_rate_id', 'value' => 1, 'group' => 'defaults'], // Assuming No Tax is ID 1
            // Add more settings as needed (e.g., email settings, invoice templates)
            ['key' => 'inventory_accounting_method', 'value' => 'fifo', 'group' => 'accounting'], // fifo, lifo, average_cost
            ['key' => 'cogs_account_id', 'value' => Account::where('code', '5010')->first()?->id, 'group' => 'accounting'],
            ['key' => 'sales_account_id', 'value' => Account::where('code', '4010')->first()?->id, 'group' => 'accounting'],
            ['key' => 'inventory_account_id', 'value' => Account::where('code', '1400')->first()?->id, 'group' => 'accounting'],
            ['key' => 'accounts_receivable_account_id', 'value' => Account::where('code', '1200')->first()?->id, 'group' => 'accounting'],
            ['key' => 'accounts_payable_account_id', 'value' => Account::where('code', '2010')->first()?->id, 'group' => 'accounting'],
        ];

        foreach ($settings as $setting) {
            // Only create if the value is not null (especially for foreign keys)
            if ($setting['value'] !== null) {
                Setting::updateOrCreate(
                    ['key' => $setting['key']],
                    ['value' => $setting['value'], 'group' => $setting['group']]
                );
            } elseif (!Setting::where('key', $setting['key'])->exists()) {
                // Create with null value only if it doesn't exist yet
                Setting::create($setting);
            }
        }
    }
}
