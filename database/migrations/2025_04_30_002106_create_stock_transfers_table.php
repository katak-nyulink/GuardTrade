<?php

use App\Enums\StockTransferStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('reference_no')->unique();
            $table->foreignId('from_warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            $table->foreignId('to_warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // User initiating

            $table->integer('total_items')->default(0);
            $table->decimal('total_quantity', 15, 4)->default(0);
            $table->decimal('total_cost', 15, 2)->nullable(); // Optional: based on product cost
            $table->decimal('shipping_cost', 15, 2)->default(0);

            $table->enum('status', array_column(StockTransferStatusEnum::cases(), 'value'))->default(StockTransferStatusEnum::PENDING->value);

            $table->text('notes')->nullable();
            $table->string('document_path')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        // Add check constraint using raw SQL
        DB::statement('ALTER TABLE stock_transfers ADD CONSTRAINT check_different_warehouses CHECK (from_warehouse_id <> to_warehouse_id)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the constraint before dropping the table
        DB::statement('ALTER TABLE stock_transfers DROP CONSTRAINT IF EXISTS check_different_warehouses');

        Schema::dropIfExists('stock_transfers');
    }
};
