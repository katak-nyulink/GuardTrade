<?php

namespace App\Models;

use App\Enums\PaymentStatusEnum;
use App\Enums\SaleStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'date',
        'reference_no', // Unique reference for the sale
        'customer_id',
        'warehouse_id',
        'user_id', // Cashier/Salesperson
        'total_amount', // Grand total after discounts and taxes
        'tax_rate', // Overall tax rate applied (if applicable)
        'tax_amount',
        'discount_type', // 'fixed' or 'percentage'
        'discount_amount',
        'shipping_cost',
        'paid_amount',
        'due_amount',
        'payment_status',
        'sale_status',
        'payment_method', // e.g., 'cash', 'card', 'credit'
        'notes',
        'document_path', // Optional: attached invoice/receipt PDF
    ];

    protected $casts = [
        'date' => 'date',
        'total_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
        'payment_status' => PaymentStatusEnum::class,
        'sale_status' => SaleStatusEnum::class,
    ];

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

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function saleReturns()
    {
        return $this->hasMany(SaleReturn::class);
    }

    // Accessor to calculate due amount automatically if needed
    // public function getDueAmountAttribute() { ... }

    // Scope for filtering, e.g., scopeCompleted, scopePaid, etc.
    public function scopeCompleted($query)
    {
        return $query->where('sale_status', SaleStatusEnum::COMPLETED);
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', PaymentStatusEnum::PAID);
    }
}
