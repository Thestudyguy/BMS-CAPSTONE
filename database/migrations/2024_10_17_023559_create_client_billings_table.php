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
    Schema::create('client_billings', function (Blueprint $table) {
        $table->id();
        $table->string('billing_id');
        $table->unsignedBigInteger('client_id')->nullable();
        $table->foreign('client_id')->references('id')->on('clients')->nullOnDelete();
        $table->unsignedBigInteger('client_services_id')->nullable();
        $table->foreign('client_services_id')->references('id')->on('client_services')->nullOnDelete();
        $table->unsignedBigInteger('added_description_id')->nullable();
        $table->foreign('added_description_id')->references('id')->on('client_additional_descriptions')->nullOnDelete();
        $table->date('due_date');
        $table->timestamps();
    });
    
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_billings');
    }
};
