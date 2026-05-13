<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settlement_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
            $table->date('period_start');
            $table->date('period_end');
            $table->string('status', 30)->default('pending')->comment('pending|processing|paid|failed');
            $table->char('currency_code', 3);
            $table->unsignedBigInteger('total_gross_minor')->default(0);
            $table->unsignedBigInteger('total_deductions_minor')->default(0);
            $table->unsignedBigInteger('total_net_minor')->default(0);
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['vendor_id', 'status']);
        });

        Schema::create('settlement_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('settlement_batches')->cascadeOnDelete();
            $table->foreignId('order_item_id')->constrained()->cascadeOnDelete();
            $table->char('currency_code', 3);
            $table->unsignedBigInteger('gross_minor');
            $table->unsignedBigInteger('commission_minor')->default(0);
            $table->unsignedBigInteger('shipping_fee_minor')->default(0);
            $table->unsignedBigInteger('ads_fee_minor')->default(0);
            $table->unsignedBigInteger('refunds_minor')->default(0);
            $table->unsignedBigInteger('net_minor');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settlement_lines');
        Schema::dropIfExists('settlement_batches');
    }
};
