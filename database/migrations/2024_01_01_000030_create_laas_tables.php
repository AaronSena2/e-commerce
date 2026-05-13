<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laas_customers', function (Blueprint $table) {
            $table->id();
            $table->string('company_name', 200);
            $table->foreignId('contact_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('billing_type', 20)->default('prepaid')->comment('prepaid|monthly');
            $table->char('currency_code', 3)->nullable();
            $table->unsignedBigInteger('wallet_balance_minor')->default(0)->comment('Prepaid wallet balance in minor units');
            $table->string('status', 30)->default('active')->comment('active|suspended|inactive');
            $table->timestamps();
        });

        Schema::create('laas_shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laas_customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('carrier_id')->nullable()->constrained()->nullOnDelete();
            $table->string('tracking_number', 100)->nullable()->index();
            $table->string('status', 40)->default('label_created')
                ->comment('label_created|picked_up|in_transit|at_station|out_for_delivery|delivered|delivery_failed|lost|damaged|return_to_sender');
            $table->char('currency_code', 3);
            $table->unsignedBigInteger('price_minor');
            $table->json('origin_json')->nullable();
            $table->json('destination_json')->nullable();
            $table->timestamps();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laas_customer_id')->constrained()->cascadeOnDelete();
            $table->string('invoice_number', 50)->unique();
            $table->date('period_start');
            $table->date('period_end');
            $table->char('currency_code', 3);
            $table->unsignedBigInteger('total_minor');
            $table->string('status', 30)->default('draft')->comment('draft|issued|paid|overdue|cancelled');
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('invoice_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->foreignId('laas_shipment_id')->nullable()->constrained()->nullOnDelete();
            $table->string('description', 300)->nullable();
            $table->char('currency_code', 3);
            $table->unsignedBigInteger('amount_minor');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_lines');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('laas_shipments');
        Schema::dropIfExists('laas_customers');
    }
};
