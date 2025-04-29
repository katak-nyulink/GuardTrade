<?php

return [
    'account_type' => [
        'asset' => 'Aset',
        'liability' => 'Kewajiban',
        'equity' => 'Modal',
        'revenue' => 'Pendapatan',
        'expense' => 'Pengeluaran',
        'other' => 'Lainnya',
    ],
    'payment_status' => [
        'pending' => 'Tertunda',
        'paid' => 'Dibayar',
        'partial' => 'Sebagian',
        'due' => 'Jatuh Tempo',
    ],
    'purchase_status' => [
        'pending' => 'Tertunda',
        'ordered' => 'Dipesan',
        'received' => 'Diterima',
        'partial_received' => 'Diterima Sebagian',
        'returned' => 'Dikembalikan',
    ],
    'sale_status' => [
        'pending' => 'Tertunda',
        'completed' => 'Selesai',
        'shipped' => 'Dikirim',
        'returned' => 'Dikembalikan',
    ],
    'stock_transfer_status' => [
        'pending' => 'Tertunda',
        'sent' => 'Terkirim',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ],
    'product_type' => [
        'standard' => 'Standar',
        'service' => 'Layanan',
        'combo' => 'Kombo',
    ],
];
