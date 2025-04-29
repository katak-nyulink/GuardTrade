<?php

namespace App\Models;

use App\Enums\AccountTypeEnum;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    /** @use HasFactory<\Database\Factories\AccountFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code', // Account code/number
        'type', // Asset, Liability, Equity, Revenue, Expense
        'description',
        'parent_id', // For hierarchical chart of accounts
        'is_enabled',
        'is_header', // To group accounts without allowing direct posting
    ];

    protected $casts = [
        'type' => AccountTypeEnum::class,
        'is_enabled' => 'boolean',
        'is_header' => 'boolean',
    ];

    /**
     * Get the parent account.
     */
    public function parent()
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    /**
     * Get the child accounts.
     */
    public function children()
    {
        return $this->hasMany(Account::class, 'parent_id');
    }

    /**
     * Get the journal entry details associated with this account.
     */
    public function journalDetails()
    {
        return $this->hasMany(JournalEntryDetail::class);
    }

    /**
     * Calculate the balance of the account.
     * This is a simplified example; a robust implementation might involve summing details
     * or maintaining a running balance.
     */
    public function getBalanceAttribute(): float
    {
        $debits = $this->journalDetails()->sum('debit');
        $credits = $this->journalDetails()->sum('credit');

        // Basic balance calculation based on account type
        return match ($this->type) {
            AccountTypeEnum::ASSET, AccountTypeEnum::EXPENSE => $debits - $credits,
            AccountTypeEnum::LIABILITY, AccountTypeEnum::EQUITY, AccountTypeEnum::REVENUE => $credits - $debits,
            default => 0,
        };
    }
}
