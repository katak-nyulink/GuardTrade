<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class StockTransferDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_transfer_id',
        'product_id',
        'product_name',
        'product_code',
        'quantity',
        'unit_cost', // Cost at the time of transfer
        'sub_total', // quantity * unit_cost
        'transfer_unit_id', // Unit used for transfer (should match product base unit ideally)
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'unit_cost' => 'decimal:4',
        'sub_total' => 'decimal:4',
    ];

    public function stockTransfer()
    {
        return $this->belongsTo(StockTransfer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function transferUnit()
    {
        return $this->belongsTo(Unit::class, 'transfer_unit_id')->withTrashed();
    }
}
