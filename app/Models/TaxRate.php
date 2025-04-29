<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxRate extends Model
{
    /** @use HasFactory<\Database\Factories\TaxRateFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'rate', // Percentage (e.g., 10 for 10%)
        'is_default',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'is_default' => 'boolean',
    ];

    // Relationships (e.g., if products have specific tax rates)
    // public function products() { ... }
}
