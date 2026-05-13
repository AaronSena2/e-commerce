<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sku_id')->constrained()->restrictOnDelete();
            $table->foreignId('vendor_id')->constrained()->restrictOnDelete();
            $table->unsignedSmallInteger('qty');
            $table->char('currency_code', 3);
            $table->unsignedBigInteger('unit_price_minor')->comment('Price per unit in minor units at time of order');
            $table->string('fulfillment_type', 20)->comment('express|dropship');
            $table->string('status', 30)->default('pending')
                ->comment('pending|confirmed|picking|packed|shipped|delivered|cancelled|return_requested|returned');
            $table->timestamps();

            $table->index(['order_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
