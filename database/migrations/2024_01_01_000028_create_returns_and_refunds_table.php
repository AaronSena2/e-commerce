<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('return_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_item_id')->constrained()->cascadeOnDelete();
            $table->string('reason_code', 50)->comment('wrong_item|damaged|not_as_described|change_of_mind|other');
            $table->text('notes')->nullable();
            $table->string('status', 30)->default('requested')
                ->comment('requested|approved|rejected|in_transit|received|refunded|closed');
            $table->timestamp('requested_at');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->timestamps();
        });

        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('return_request_id')->nullable()->constrained()->nullOnDelete();
            $table->char('currency_code', 3);
            $table->unsignedBigInteger('amount_minor');
            $table->string('status', 30)->default('pending')
                ->comment('pending|processing|completed|failed');
            $table->string('provider_ref', 200)->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refunds');
        Schema::dropIfExists('return_requests');
    }
};
