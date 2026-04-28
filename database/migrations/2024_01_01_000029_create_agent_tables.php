<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agent_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('region_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status', 30)->default('active')->comment('active|suspended|inactive');
            $table->timestamps();
        });

        Schema::create('assisted_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('agent_id')->constrained('agent_profiles')->cascadeOnDelete();
            $table->string('customer_phone', 20)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('agent_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('agent_profiles')->cascadeOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->char('currency_code', 3);
            $table->unsignedBigInteger('amount_minor');
            $table->string('status', 30)->default('pending')->comment('pending|earned|paid');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agent_commissions');
        Schema::dropIfExists('assisted_orders');
        Schema::dropIfExists('agent_profiles');
    }
};
