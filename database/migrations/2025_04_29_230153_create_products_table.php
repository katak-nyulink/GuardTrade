<?php

use App\Enums\ProductTypeEnum;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique()->nullable();
            $table->string('code')->unique()->nullable(); // SKU / Barcode
            $table->enum('type', array_column(ProductTypeEnum::cases(), 'value'))->default(ProductTypeEnum::STANDARD->value);
            $table->text('description')->nullable();
            $table->decimal('cost', 15, 4)->default(0);
            $table->decimal('price', 15, 4)->default(0);

            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('brand_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('sale_unit_id')->nullable()->constrained('units')->onDelete('set null');
            $table->foreignId('purchase_unit_id')->nullable()->constrained('units')->onDelete('set null');
            $table->foreignId('tax_rate_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('tax_method', ['inclusive', 'exclusive'])->default('exclusive');

            $table->string('image_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable(); // Internal notes

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
