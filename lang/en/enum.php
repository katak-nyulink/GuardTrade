<?php

return [
    'account_type' => [
        'asset' => 'Asset',
        'liability' => 'Liability',
        'equity' => 'Equity',
        'revenue' => 'Revenue',
        'expense' => 'Expense',
        'other' => 'Other',
    ],
    'payment_status' => [
        'pending' => 'Pending',
        'paid' => 'Paid',
        'partial' => 'Partial',
        'due' => 'Due',

    ],
    'purchase_status' => [
        'pending' => 'Pending',
        'ordered' => 'Ordered',
        'received' => 'Received',
        'partial_received' => 'Partial Received',
        'returned' => 'Returned',
    ],
    'sale_status' => [
        'pending' => 'Pending',
        'completed' => 'Completed',
        'shipped' => 'shipped',
        'returned' => 'Returned',
    ],
    'stock_transfer_status' => [
        'pending' => 'Pending',
        'sent' => 'Sent',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ],
    'product_type' => [
        'standard' => 'Standard',
        'service' => 'Service',
        'combo' => 'Combo',
    ],
];
