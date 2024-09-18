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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('CompanyName');
            $table->string('CompanyAddress');
            $table->string('TIN');
            $table->string('CompanyEmail');
            $table->string('CEO');
            $table->string('CEODateOfBirth');
            $table->string('CEOContactInformation');//phone number or email
            $table->string(column: 'dataEntryUser');
            $table->boolean('isVisible')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
