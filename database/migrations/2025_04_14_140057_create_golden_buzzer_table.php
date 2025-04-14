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
        Schema::create('golden_buzzer_songs', function (Blueprint $table)
        {
            $table->id();
            $table->comment('Eligibility for the Golden Buzzer.');
            $table->foreignId('song_id')->constrained('golden_buzzer_songs')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('golden_buzzers', function (Blueprint $table)
        {
            $table->id();
            $table->foreignId('song_id')->constrained('golden_buzzer_songs')->cascadeOnDelete();
            $table->decimal('amount');
            $table->string('transaction_id')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('golden_buzzers');
        Schema::dropIfExists('golden_buzzer_songs');
    }
};
