<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('sku_code', 100)->unique();
            $table->char('currency_code', 3);
            $table->unsignedBigInteger('price_minor')->comment('Price in currency minor units (e.g., cents)');
            $table->unsignedBigInteger('sale_price_minor')->nullable()->comment('Sale price in minor units; null means no active sale');
            $table->json('attributes_json')->nullable()->comment('Variant attributes, e.g. {"color":"red","size":"M"}');
            $table->boolean('express_eligible')->default(false)->comment('Eligible for Jumia Express (consignment) fulfillment');
            $table->string('status', 30)->default('active')->comment('active|inactive|discontinued');
            $table->timestamps();

            $table->index(['product_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skus');
    }
};
