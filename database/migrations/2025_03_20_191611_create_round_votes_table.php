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
        Schema::create('round_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('round_id')->constrained('rounds', 'id')->cascadeOnDelete();
            $table->foreignId('first_choice_id')->references('id')->on('songs')->cascadeOnDelete();
            $table->foreignId('second_choice_id')->references('id')->on('songs')->cascadeOnDelete();
            $table->foreignId('third_choice_id')->references('id')->on('songs')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('round_votes');
    }
};
