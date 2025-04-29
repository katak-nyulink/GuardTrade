<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'product_id',
        'product_name', // Denormalized for easier display
        'product_code', // Denormalized
        'quantity',
        'unit_price', // Price per unit before discount/tax
        'sub_total', // quantity * unit_price
        'discount_type', // 'fixed' or 'percentage' per item
        'discount_amount',
        'tax_rate', // Tax rate % for this item
        'tax_amount',
        'total_amount', // Amount for this line item after discount & tax
        'sale_unit_id', // Unit used for this sale item
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

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function product()
    {
        // Use withTrashed if you want to show details even if product is deleted
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function saleUnit()
    {
        return $this->belongsTo(Unit::class, 'sale_unit_id')->withTrashed();
    }
}
