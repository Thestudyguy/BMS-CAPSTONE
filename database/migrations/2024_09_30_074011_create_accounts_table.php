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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('AccountName');
            $table->unsignedBigInteger(column: 'AccountType')->nullable();
            $table->foreign('AccountType')->references('id')->on('account_types')->nullOnDelete();
            // $table->string('Category');
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
        Schema::dropIfExists('accounts');
    }
};
