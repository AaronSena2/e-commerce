<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipment_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_item_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('qty');
            $table->timestamps();
        });

        Schema::create('tracking_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained()->cascadeOnDelete();
            $table->string('code', 50)
                ->comment('PACKED|HANDED_TO_CARRIER|ARRIVED_SORT_CENTER|ARRIVED_REGIONAL|OUT_FOR_DELIVERY|DELIVERED|DELIVERY_FAILED|READY_FOR_PICKUP|PICKED_UP');
            $table->string('description', 300)->nullable();
            $table->foreignId('location_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('occurred_at');
            $table->timestamps();

            $table->index(['shipment_id', 'occurred_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracking_events');
        Schema::dropIfExists('shipment_items');
    }
};
