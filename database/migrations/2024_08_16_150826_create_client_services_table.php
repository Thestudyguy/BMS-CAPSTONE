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
        Schema::create('client_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('Client')->nullable();
            $table->foreign('Client')->references('id')->on('clients')->nullOnDelete();
            $table->string('ClientService');
            $table->string('ClientServiceProgress');
            $table->string('getClientOriginalName')->nullable();
            $table->string('getClientMimeType')->nullable();
            $table->string('getSize')->nullable();
            $table->string('getRealPath')->nullable();
            $table->string('dataEntryUser');
            $table->string('isClientNotified')->default(false);
            $table->boolean('isVisible')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_services');
    }
};
