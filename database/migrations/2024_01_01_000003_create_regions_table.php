<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('code', 20)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['country_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regions');
    }
};
