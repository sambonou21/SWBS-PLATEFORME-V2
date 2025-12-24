<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('pending');
            $table->decimal('total_amount_fcfa', 12, 2);
            $table->string('currency', 10)->default('FCFA');
            $table->decimal('exchange_rate', 12, 6)->default(1);
            $table->decimal('total_amount_currency', 12, 2)->nullable();
            $table->string('payment_provider')->default('fedepay');
            $table->string('payment_reference')->nullable();
            $table->json('payment_payload')->nullable();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone')->nullable();
            $table->text('customer_address')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};