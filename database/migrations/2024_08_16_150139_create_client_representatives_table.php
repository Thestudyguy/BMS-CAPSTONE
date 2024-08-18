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
        Schema::create('client_representatives', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('CompanyRepresented')->nullable();//set to nullable for archives
            $table->foreign('CompanyRepresented')->references('id')->on('clients')->nullOnDelete();
            $table->string('RepresentativeName');
            $table->string('RepresentativeContactInformation');
            $table->string('RepresentativeDateOfBirth');
            $table->string('RepresentativePosition');
            $table->string('RepresentativeAddress');
            $table->string('dataEntryUser');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_representatives');
    }
};
