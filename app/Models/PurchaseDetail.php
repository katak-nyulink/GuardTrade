<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'product_id',
        'product_name', // Denormalized
        'product_code', // Denormalized
        'quantity',
        'received_quantity', // Quantity actually received (can differ from ordered)
        'unit_cost', // Cost per unit before discount/tax
        'sub_total', // quantity * unit_cost
        'discount_type',
        'discount_amount',
        'tax_rate',
        'tax_amount',
        'total_amount', // Amount for this line item after discount & tax
        'purchase_unit_id', // Unit used for this purchase item
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'received_quantity' => 'decimal:4',
        'unit_cost' => 'decimal:4', // Higher precision for cost
        'sub_total' => 'decimal:4',
        'discount_amount' => 'decimal:4',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:4',
        'total_amount' => 'decimal:4',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
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
