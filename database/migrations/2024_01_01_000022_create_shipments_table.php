<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('carrier_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('origin_location_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->string('shipment_number', 50)->unique();
            $table->string('tracking_number', 100)->nullable()->index();
            $table->string('status', 40)->default('label_created')
                ->comment('label_created|picked_up|in_transit|at_station|out_for_delivery|delivered|delivery_failed|lost|damaged|return_to_sender');
            $table->string('dest_address_line1', 300)->nullable();
            $table->string('dest_address_line2', 300)->nullable();
            $table->string('dest_city', 100)->nullable();
            $table->string('dest_country_code', 3)->nullable();
            $table->timestamps();

            $table->index(['order_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
