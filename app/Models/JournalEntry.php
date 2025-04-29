<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'date',
        'reference', // Optional reference (e.g., INV-001, Manual Entry)
        'description',
        'user_id', // User who created the entry
        'sourceable_type', // Polymorphic relation: Sale, Purchase, SaleReturn, etc. or null for manual
        'sourceable_id',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the source model (Sale, Purchase, etc.) that triggered this entry.
     */
    public function sourceable()
    {
        return $this->morphTo();
    }

    /**
     * Get the details (debit/credit lines) for the journal entry.
     */
    public function details()
    {
        return $this->hasMany(JournalEntryDetail::class);
    }

    /**
     * Check if the journal entry is balanced (total debits == total credits).
     */
    public function isBalanced(): bool
    {
        $debits = $this->details()->sum('debit');
        $credits = $this->details()->sum('credit');
        // Use a small tolerance for floating point comparisons
        return abs($debits - $credits) < 0.0001;
    }
}
