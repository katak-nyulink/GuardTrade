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
        Schema::create('sale_return_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_return_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sale_detail_id')->nullable()->constrained()->nullOnDelete(); // Optional link to original line
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('product_name');
            $table->string('product_code')->nullable();

            $table->decimal('quantity', 15, 4); // Returned quantity
            $table->decimal('unit_price', 15, 4); // Price from original sale
            $table->decimal('sub_total', 15, 4); // quantity * unit_price

            $table->enum('discount_type', ['fixed', 'percentage'])->nullable();
            $table->decimal('discount_amount', 15, 4)->default(0);

            $table->decimal('tax_rate', 5, 2)->nullable();
            $table->decimal('tax_amount', 15, 4)->default(0);

            $table->decimal('total_amount', 15, 4); // Line total value returned

            $table->foreignId('sale_unit_id')->nullable()->constrained('units')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_return_details');
    }
};
