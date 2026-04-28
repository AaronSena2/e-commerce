<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cod_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->json('signals_json')->nullable()
                ->comment('Risk signals: address_reliability, device_risk, customer_history, region_cod_loss_rate, etc.');
            $table->unsignedSmallInteger('risk_score')->default(0)->comment('0-100; higher = riskier');
            $table->string('decision', 30)->default('pending')
                ->comment('pending|approved|denied|deposit_required');
            $table->char('currency_code', 3)->nullable();
            $table->unsignedBigInteger('deposit_amount_minor')->nullable();
            $table->timestamp('decided_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cod_requests');
    }
};
