<?php

use App\Enums\AccountTypeEnum;
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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique()->nullable(); // Chart of Accounts code
            $table->enum('type', array_column(AccountTypeEnum::cases(), 'value'));
            $table->text('description')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('accounts')->onDelete('cascade');
            $table->boolean('is_enabled')->default(true);
            $table->boolean('is_header')->default(false); // Cannot post directly if true
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
