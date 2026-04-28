<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commission_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->unsignedSmallInteger('rate_min_bps')->comment('Minimum commission in basis points');
            $table->unsignedSmallInteger('rate_max_bps')->comment('Maximum commission in basis points');
            $table->json('rules_json')->nullable()->comment('Category or condition-specific overrides');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commission_plans');
    }
};
