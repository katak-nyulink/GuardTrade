<?php

namespace App\Models;

use App\Enums\StockTransferStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class StockTransfer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'date',
        'reference_no',
        'from_warehouse_id',
        'to_warehouse_id',
        'user_id', // User initiating the transfer
        'total_items', // Total distinct items
        'total_quantity', // Total quantity of all items
        'total_cost', // Optional: Total cost of transferred items
        'shipping_cost',
        'status',
        'notes',
        'document_path', // Optional: Transfer document
    ];

    protected $casts = [
        'date' => 'date',
        'total_items' => 'integer',
        'total_quantity' => 'decimal:4',
        'total_cost' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'status' => StockTransferStatusEnum::class,
    ];

    public function fromWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }

    public function toWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(StockTransferDetail::class);
    }
}
