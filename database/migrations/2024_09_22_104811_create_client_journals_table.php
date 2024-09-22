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
        Schema::create('client_journals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client')->nullable();
            $table->foreign('client')->references('id')->on('clients')->nullOnDelete();
            $table->year('start_date');
            $table->year('end_date');
            $table->string('month');
            $table->decimal('amount', 15)->default(0);
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
        Schema::dropIfExists('client_journals');
    }
};
