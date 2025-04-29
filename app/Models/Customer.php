<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'postal_code',
        'points', // Optional: for loyalty programs
    ];

    protected $casts = [
        'points' => 'integer',
    ];

    /**
     * Get the sales made to this customer.
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Get the sale returns made by this customer.
     */
    public function saleReturns()
    {
        return $this->hasMany(SaleReturn::class);
    }
}
