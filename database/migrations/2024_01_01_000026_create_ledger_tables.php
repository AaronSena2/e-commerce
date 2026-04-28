<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ledger_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('type', 20)->comment('asset|liability|revenue|expense|equity');
            $table->string('description', 300)->nullable();
            $table->timestamps();
        });

        Schema::create('ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('ledger_accounts')->cascadeOnDelete();
            $table->string('direction', 10)->comment('debit|credit');
            $table->char('currency_code', 3);
            $table->unsignedBigInteger('amount_minor');
            $table->string('reference_type', 50)->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('description', 300)->nullable();
            $table->timestamps();

            $table->index(['reference_type', 'reference_id']);
            $table->index('account_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ledger_entries');
        Schema::dropIfExists('ledger_accounts');
    }
};
