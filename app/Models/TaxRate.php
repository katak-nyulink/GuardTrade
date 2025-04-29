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
}
