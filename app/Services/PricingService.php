<?php

namespace App\Services;

use App\Models\SaleOrder;
use Illuminate\Support\Number;

class PricingService
{
    public function formatOrderPrices(SaleOrder $salesOrder, string $displayCurrency)
    {
        return Number::withCurrency($displayCurrency, function () use ($salesOrder) {
            return [
                'subtotal' => Number::currency($salesOrder->subtotal),
                'tax' => Number::currency($salesOrder->tax),
                'shipping' => Number::currency($salesOrder->shipping_cost),
                'total' => Number::currency($salesOrder->total),
                'savings' => $this->calculateDiscounts($salesOrder)
            ];
        });
    }

    private function calculateDiscounts(SaleOrder $order): array
    {
        return [
            'bulk_discount' => Number::currency($order->bulk_discount),
            'loyalty_savings' => Number::currency($order->loyalty_discount),
            'total_saved' => Number::currency(
                $order->bulk_discount + $order->loyalty_discount
            )
        ];
    }
}
