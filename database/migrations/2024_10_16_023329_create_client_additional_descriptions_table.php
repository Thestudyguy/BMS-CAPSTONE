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
        Schema::create('client_additional_descriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('description')->nullable();
            $table->foreign('description')->references('id')->on('account_descriptions')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_additional_descriptions');
    }
};
