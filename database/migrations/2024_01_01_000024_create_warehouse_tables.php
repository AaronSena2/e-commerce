<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouse_pick_waves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained()->cascadeOnDelete();
            $table->string('status', 30)->default('open')->comment('open|in_progress|completed|cancelled');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('warehouse_pick_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wave_id')->constrained('warehouse_pick_waves')->cascadeOnDelete();
            $table->foreignId('order_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sku_id')->constrained()->cascadeOnDelete();
            $table->foreignId('location_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('qty_required');
            $table->unsignedSmallInteger('qty_picked')->default(0);
            $table->string('status', 30)->default('pending')->comment('pending|picked|short_picked|skipped');
            $table->timestamp('picked_at')->nullable();
            $table->timestamps();
        });

        Schema::create('warehouse_pack_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('location_id')->constrained()->cascadeOnDelete();
            $table->string('status', 30)->default('pending')->comment('pending|packed|failed_qc');
            $table->string('box_reference', 100)->nullable();
            $table->foreignId('packed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('packed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouse_pack_tasks');
        Schema::dropIfExists('warehouse_pick_tasks');
        Schema::dropIfExists('warehouse_pick_waves');
    }
};
