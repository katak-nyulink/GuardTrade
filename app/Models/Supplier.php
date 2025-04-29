<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    /** @use HasFactory<\Database\Factories\SupplierFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'company_name',
        'vat_number',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'postal_code',
    ];

    /**
     * Get the purchases made from this supplier.
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * Get the purchase returns made to this supplier.
     */
    public function purchaseReturns()
    {
        return $this->hasMany(PurchaseReturn::class);
    }
}
