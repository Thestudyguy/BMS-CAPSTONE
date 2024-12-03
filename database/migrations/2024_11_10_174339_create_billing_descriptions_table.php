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
        Schema::create('billing_descriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->foreign('client_id')->references('id')->on('clients')->nullOnDelete();
            $table->string('billing_id');
            $table->string('account');
            $table->string('category');
            $table->decimal('amount', 15);
            $table->unsignedBigInteger('sub_service')->nullable();
            $table->foreign('sub_service')->references('id')->on('client_billing_sub_services')->nullOnDelete();
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
        Schema::dropIfExists('billing_descriptions');
    }
};
