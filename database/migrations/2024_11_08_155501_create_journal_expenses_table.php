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
        Schema::create('journal_expenses', function (Blueprint $table) {
            $table->id();
            $table->string('account');
            $table->string('month');
            $table->decimal('amount', 15);
            $table->date('start_date');
            $table->date('end_date');
            $table->string('journal_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_expenses');
    }
};
