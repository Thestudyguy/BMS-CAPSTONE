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
        Schema::create('sub_service_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_id')->nullable();
            $table->foreign('service_id')->references('id')->on('sub_service_requirements')->nullOnDelete();
            $table->unsignedBigInteger('client_service')->nullable();
            $table->foreign('client_service')->references('id')->on('client_services')->nullOnDelete();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->foreign('client_id')->references('id')->on('clients')->nullOnDelete();
            $table->string('ReqName');
            $table->string('getClientOriginalName')->nullable();
            $table->string('getClientMimeType')->nullable();
            $table->string('getSize')->nullable();
            $table->string('getRealPath')->nullable();
            $table->string('dataEntryUser');
            $table->boolean('isVisible')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_service_documents');
    }
};
