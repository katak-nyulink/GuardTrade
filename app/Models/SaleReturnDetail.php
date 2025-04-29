<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleReturnDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_return_id',
        'sale_detail_id', // Link to original sale detail line (optional but useful)
        'product_id',
        'product_name',
        'product_code',
        'quantity', // Quantity returned
        'unit_price', // Price at time of original sale
        'sub_total', // quantity * unit_price
        'discount_type',
        'discount_amount',
        'tax_rate',
        'tax_amount',
        'total_amount', // Value of the returned item line
        'sale_unit_id',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'unit_price' => 'decimal:2',
        'sub_total' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function saleReturn()
    {
        return $this->belongsTo(SaleReturn::class);
    }

    public function saleDetail()
    {
        return $this->belongsTo(SaleDetail::class); // Optional link
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function saleUnit()
    {
        return $this->belongsTo(Unit::class, 'sale_unit_id')->withTrashed();
    }
}
