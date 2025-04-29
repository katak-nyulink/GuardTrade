<?php

namespace App\Models;

use App\Enums\PaymentStatusEnum;
use App\Enums\PurchaseStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'date',
        'reference_no', // Unique reference for the purchase
        'supplier_id',
        'warehouse_id', // Warehouse receiving the stock
        'user_id', // User who created the purchase
        'total_amount', // Grand total after discounts and taxes
        'tax_rate',
        'tax_amount',
        'discount_type',
        'discount_amount',
        'shipping_cost',
        'paid_amount',
        'due_amount',
        'payment_status',
        'purchase_status',
        'payment_method',
        'notes',
        'document_path', // Optional: attached PO/invoice PDF
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
        'purchase_status' => PurchaseStatusEnum::class,
    ];

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

    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    public function purchaseReturns()
    {
        return $this->hasMany(PurchaseReturn::class);
    }

    // Scopes like scopeReceived, scopeDue, etc.
    public function scopeReceived($query)
    {
        return $query->where('purchase_status', PurchaseStatusEnum::RECEIVED);
    }
}
