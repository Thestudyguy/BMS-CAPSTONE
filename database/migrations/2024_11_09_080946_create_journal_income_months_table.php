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
        Schema::create('journal_income_months', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('income_id')->nullable();
            $table->foreign('income_id')->references('id')->on('journal_incomes')->nullOnDelete();
            $table->string('month');
            $table->decimal('amount', 15);
            $table->boolean('isAltered')->default(false);
            $table->boolean('has_reset')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_income_months');
    }
};
