<?php

namespace App\Models;

use App\Enums\ProductTypeEnum;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'code', // SKU or Barcode
        'type', // standard, service, combo
        'description',
        'cost', // Purchase cost
        'price', // Selling price
        'category_id',
        'brand_id',
        'sale_unit_id', // Unit for selling
        'purchase_unit_id', // Unit for purchasing
        'tax_rate_id', // Optional: specific tax rate
        'tax_method', // 'inclusive' or 'exclusive'
        'image_path',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'cost' => 'decimal:2',
        'price' => 'decimal:2',
        'type' => ProductTypeEnum::class,
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function saleUnit()
    {
        return $this->belongsTo(Unit::class, 'sale_unit_id');
    }

    public function purchaseUnit()
    {
        return $this->belongsTo(Unit::class, 'purchase_unit_id');
    }

    public function taxRate()
    {
        return $this->belongsTo(TaxRate::class);
    }

    /**
     * Get the warehouses stocking this product.
     */
    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class, 'product_warehouse')
            ->withPivot('quantity', 'alert_quantity')
            ->withTimestamps();
    }

    /**
     * Get stock quantity in a specific warehouse.
     */
    public function stock(int $warehouseId): float
    {
        $pivot = $this->warehouses()->where('warehouse_id', $warehouseId)->first()?->pivot;
        return $pivot ? (float) $pivot->quantity : 0;
    }

    /**
     * Get alert quantity in a specific warehouse.
     */
    public function alertQuantity(int $warehouseId): float
    {
        $pivot = $this->warehouses()->where('warehouse_id', $warehouseId)->first()?->pivot;
        return $pivot ? (float) $pivot->alert_quantity : 0;
    }

    // Relationships for transaction details
    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    public function saleReturnDetails()
    {
        return $this->hasMany(SaleReturnDetail::class);
    }

    public function purchaseReturnDetails()
    {
        return $this->hasMany(PurchaseReturnDetail::class);
    }

    public function stockTransferDetails()
    {
        return $this->hasMany(StockTransferDetail::class);
    }
}
