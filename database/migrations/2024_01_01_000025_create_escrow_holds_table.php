<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('escrow_holds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->char('currency_code', 3);
            $table->unsignedBigInteger('amount_minor')->comment('Gross order amount held in escrow (minor units)');
            $table->string('status', 30)->default('holding')->comment('holding|released|blocked');
            $table->date('delivery_date')->nullable()->comment('T: confirmed delivery date');
            $table->date('release_date')->nullable()->comment('T+7: scheduled escrow release date');
            $table->timestamp('released_at')->nullable();
            $table->timestamps();

            $table->index('release_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('escrow_holds');
    }
};
