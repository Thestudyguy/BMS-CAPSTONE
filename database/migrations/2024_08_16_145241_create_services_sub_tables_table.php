<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('services_sub_tables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('BelongsToService');
            $table->foreign('BelongsToService')->references('id')->on('services')->cascadeOnDelete();
            $table->string('ServiceRequirements');
            $table->decimal('ServiceRequirementPrice', 15)->nullable();
            $table->string('dataEntryUser')->default(null);
            $table->boolean('isVisible')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services_sub_tables');
        Schema::table('services_sub_tables', function (Blueprint $table) {
            $table->decimal('ServiceRequirementPrice', 8)->change();
        });
    }
};
