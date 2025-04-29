<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('journal_entry_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_entry_id')->constrained()->cascadeOnDelete();
            $table->foreignId('account_id')->constrained()->cascadeOnDelete(); // Link to accounts table
            $table->decimal('debit', 15, 2)->nullable(); // Debit amount
            $table->decimal('credit', 15, 2)->nullable(); // Credit amount
            $table->string('description')->nullable(); // Line item description
            $table->timestamps();

            // Ensure either debit or credit is set, and they are not negative
            // DB constraints might be needed depending on DB engine
            // Add index on account_id for performance
            $table->index('account_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_entry_details');
    }
};
