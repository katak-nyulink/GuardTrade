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
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('product_name'); // Denormalized
            $table->string('product_code')->nullable(); // Denormalized

            $table->decimal('quantity', 15, 4); // Ordered quantity
            $table->decimal('received_quantity', 15, 4)->default(0); // Actually received quantity
            $table->decimal('unit_cost', 15, 4); // Cost before discount/tax
            $table->decimal('sub_total', 15, 4); // quantity * unit_cost

            $table->enum('discount_type', ['fixed', 'percentage'])->nullable();
            $table->decimal('discount_amount', 15, 4)->default(0);

            $table->decimal('tax_rate', 5, 2)->nullable(); // Item specific tax rate %
            $table->decimal('tax_amount', 15, 4)->default(0);

            $table->decimal('total_amount', 15, 4); // Line total after discount & tax

            $table->foreignId('purchase_unit_id')->nullable()->constrained('units')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_details');
    }
};
