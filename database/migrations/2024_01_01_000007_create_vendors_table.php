<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('business_name', 200);
            $table->string('status', 30)->default('pending_kyc')
                ->comment('pending_kyc|kyc_submitted|kyc_in_review|active|suspended|rejected');
            $table->foreignId('commission_plan_id')->nullable()->constrained('commission_plans')->nullOnDelete();
            $table->string('payout_account_ref', 100)->nullable();
            $table->char('currency_code', 3)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
