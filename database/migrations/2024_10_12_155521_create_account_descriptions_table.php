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
        Schema::create('account_descriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account')->nullable();
            $table->foreign('account')->references('id')->on(table: 'account_types')->nullOnDelete();
            $table->string('Category');
            $table->string('Description');
            $table->string('TaxType');
            $table->string('FormType');
            $table->decimal('Price', 15)->nullable();
            $table->boolean('isVisible')->default(true);
            $table->string('dataUserEntry');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_descriptions');
    }
};
