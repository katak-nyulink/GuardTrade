<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturnDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_return_id',
        'purchase_detail_id', // Link to original purchase detail line (optional)
        'product_id',
        'product_name',
        'product_code',
        'quantity', // Quantity returned
        'unit_cost', // Cost at time of original purchase
        'sub_total', // quantity * unit_cost
        'discount_type',
        'discount_amount',
        'tax_rate',
        'tax_amount',
        'total_amount', // Value of the returned item line
        'purchase_unit_id',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'unit_cost' => 'decimal:4',
        'sub_total' => 'decimal:4',
        'discount_amount' => 'decimal:4',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:4',
        'total_amount' => 'decimal:4',
    ];

    public function purchaseReturn()
    {
        return $this->belongsTo(PurchaseReturn::class);
    }

    public function purchaseDetail()
    {
        return $this->belongsTo(PurchaseDetail::class); // Optional link
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function purchaseUnit()
    {
        return $this->belongsTo(Unit::class, 'purchase_unit_id')->withTrashed();
    }
}
