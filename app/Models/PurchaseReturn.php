<?php

namespace App\Models;

use App\Enums\PaymentStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class PurchaseReturn extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'date',
        'reference_no', // Unique reference for the return
        'purchase_id', // Original purchase being returned
        'supplier_id',
        'warehouse_id', // Warehouse returning stock from
        'user_id', // User processing the return
        'total_amount', // Total value of returned items (usually debit/credit amount)
        'tax_rate',
        'tax_amount',
        'discount_type',
        'discount_amount',
        'shipping_cost',
        'paid_amount', // Amount received back/credited from supplier
        'payment_status', // e.g., Credited, Refund Received
        'payment_method', // How credit/refund was received
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'total_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'paid_amount' => 'decimal:2', // Amount received back
        'payment_status' => PaymentStatusEnum::class, // Adjust if needed
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function purchaseReturnDetails()
    {
        return $this->hasMany(PurchaseReturnDetail::class);
    }
}
