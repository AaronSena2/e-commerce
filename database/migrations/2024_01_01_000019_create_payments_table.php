<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('method', 30)->comment('card|mobile_money|bank_transfer|cod|wallet');
            $table->string('status', 30)->default('pending')
                ->comment('pending|authorized|captured|failed|refunded|voided');
            $table->char('currency_code', 3);
            $table->unsignedBigInteger('amount_minor');
            $table->string('provider_ref', 200)->nullable()->comment('External payment provider reference');
            $table->timestamp('authorized_at')->nullable();
            $table->timestamp('captured_at')->nullable();
            $table->timestamps();

            $table->index('order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
