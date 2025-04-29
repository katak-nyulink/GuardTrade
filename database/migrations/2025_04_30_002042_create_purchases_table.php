<?php

use App\Enums\PaymentStatusEnum;
use App\Enums\PurchaseStatusEnum;
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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('reference_no')->unique();
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete(); // Warehouse receiving goods
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // User who created

            $table->decimal('total_amount', 15, 2); // Grand Total
            $table->decimal('tax_rate', 5, 2)->nullable(); // Overall tax rate %
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->enum('discount_type', ['fixed', 'percentage'])->nullable();
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('shipping_cost', 15, 2)->default(0);

            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('due_amount', 15, 2)->default(0); // total - paid
            $table->string('payment_method')->nullable();
            $table->enum('payment_status', array_column(PaymentStatusEnum::cases(), 'value'))->default(PaymentStatusEnum::PENDING->value);
            $table->enum('purchase_status', array_column(PurchaseStatusEnum::cases(), 'value'))->default(PurchaseStatusEnum::PENDING->value);

            $table->text('notes')->nullable();
            $table->string('document_path')->nullable(); // Path to PO PDF etc.

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
