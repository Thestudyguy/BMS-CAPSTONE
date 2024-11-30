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
        Schema::create('journal_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('journal_id')->nullable();
            $table->foreign('journal_id')->references('id')->on('client_journals')->nullOnDelete();
            $table->text('note')->nullable();
            $table->unsignedBigInteger('user')->nullable();
            $table->foreign('user')->references('id')->on('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_notes');
    }
};
