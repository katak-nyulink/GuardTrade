<?php

namespace App\Models;

use App\Enums\PaymentStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class SaleReturn extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'date',
        'reference_no', // Unique reference for the return
        'sale_id', // Original sale being returned
        'customer_id',
        'warehouse_id', // Warehouse receiving returned stock
        'user_id', // User processing the return
        'total_amount', // Total value of returned items (usually negative or refund amount)
        'tax_rate',
        'tax_amount',
        'discount_type',
        'discount_amount', // Discount applied on the return itself (rare)
        'shipping_cost', // Shipping cost associated with return (if any)
        'paid_amount', // Amount refunded/credited to customer
        'payment_status', // e.g., Refunded, Pending Refund
        'payment_method', // How refund was issued
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'total_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'paid_amount' => 'decimal:2', // Amount refunded
        'payment_status' => PaymentStatusEnum::class, // Adjust if using a different enum
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function saleReturnDetails()
    {
        return $this->hasMany(SaleReturnDetail::class);
    }
}
