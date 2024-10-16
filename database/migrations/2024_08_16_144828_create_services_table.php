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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('Service');
            $table->decimal('Price', 15);
            $table->string('Category');
            $table->boolean('isVisible')->default(true);
            $table->string('dataEntryUser');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
        Schema::table('services', function (Blueprint $table) {
            $table->decimal('Price', 8)->change();
        });
    }
};
