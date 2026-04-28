<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('country_id')->nullable()->constrained()->nullOnDelete();
            $table->string('order_number', 30)->unique();
            $table->string('status', 30)->default('created')
                ->comment('created|payment_pending|confirmed|fulfilling|shipped|delivered|closed|cancelled|failed_payment|return_requested|refunded');
            $table->string('payment_method', 30)->comment('card|mobile_money|bank_transfer|cod');
            $table->char('currency_code', 3);
            $table->unsignedBigInteger('subtotal_minor');
            $table->unsignedBigInteger('shipping_total_minor')->default(0);
            $table->unsignedBigInteger('tax_total_minor')->default(0);
            $table->unsignedBigInteger('discount_total_minor')->default(0);
            $table->unsignedBigInteger('total_minor');
            $table->string('shipping_name', 200)->nullable();
            $table->string('shipping_address_line1', 300)->nullable();
            $table->string('shipping_address_line2', 300)->nullable();
            $table->string('shipping_city', 100)->nullable();
            $table->timestamps();

            $table->index(['customer_id', 'status']);
            $table->index('order_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
