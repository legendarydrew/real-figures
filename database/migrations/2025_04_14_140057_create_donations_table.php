<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table)
        {
            $table->id();
            $table->string('name');
            $table->decimal('amount');
            $table->string('currency', 3)->index();
            $table->string('transaction_id')->unique();
            $table->boolean('is_anonymous')->default(false);
            $table->text('message')->nullable();
            $table->timestamps();
        });

        Schema::create('golden_buzzers', function (Blueprint $table)
        {
            $table->id();
            $table->foreignId('round_id')->constrained('rounds')->cascadeOnDelete();
            $table->foreignId('song_id')->constrained('songs')->cascadeOnDelete();
            $table->string('name');
            $table->decimal('amount');
            $table->string('currency', 3)->index();
            $table->string('transaction_id')->unique();
            $table->boolean('is_anonymous')->default(false);
            $table->text('message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('golden_buzzers');
        Schema::dropIfExists('donations');
    }
};
