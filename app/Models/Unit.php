<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    /** @use HasFactory<\Database\Factories\UnitFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name', // e.g., Kilogram, Piece, Liter
        'short_name', // e.g., kg, pc, ltr
        'base_unit_id', // For unit conversions (e.g., Box -> Piece)
        'operator', // For conversion (*, /)
        'operator_value', // Conversion factor (e.g., 1 Box = 12 Pieces)
    ];

    /**
     * Get the products using this unit.
     */
    public function products()
    {
        // A product might have a sale unit and a purchase unit
        return $this->hasMany(Product::class, 'sale_unit_id')
            ->orWhere('purchase_unit_id', $this->id);
    }

    /**
     * Get the base unit for conversion.
     */
    public function baseUnit()
    {
        return $this->belongsTo(Unit::class, 'base_unit_id');
    }
}
