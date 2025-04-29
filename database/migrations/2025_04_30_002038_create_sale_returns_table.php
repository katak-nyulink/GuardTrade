<?php

use App\Enums\PaymentStatusEnum;
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
        Schema::create('sale_returns', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('reference_no')->unique();
            $table->foreignId('sale_id')->nullable()->constrained()->nullOnDelete(); // Link to original sale
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete(); // Warehouse receiving returned goods
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // User processing return

            $table->decimal('total_amount', 15, 2); // Total value returned (often negative or refund amount)
            $table->decimal('tax_rate', 5, 2)->nullable();
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->enum('discount_type', ['fixed', 'percentage'])->nullable();
            $table->decimal('discount_amount', 15, 2)->default(0); // Discount on the return itself (rare)
            $table->decimal('shipping_cost', 15, 2)->default(0); // Return shipping

            $table->decimal('paid_amount', 15, 2)->default(0); // Amount refunded/credited
            $table->string('payment_method')->nullable(); // How refund was made
            $table->enum('payment_status', array_column(PaymentStatusEnum::cases(), 'value'))->default(PaymentStatusEnum::PENDING->value); // e.g., Refunded

            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_returns');
    }
};
