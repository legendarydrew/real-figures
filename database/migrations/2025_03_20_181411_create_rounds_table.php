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
        Schema::create('stages', function (Blueprint $table)
        {
            $table->comment('Individual stages of the contest.');
            $table->id();
            $table->string('title')->unique();
            $table->text('description');
            $table->timestamps();
        });

        Schema::create('rounds', function (Blueprint $table)
        {
            $table->id();
            $table->foreignId('stage_id')->constrained('stages', 'id')->cascadeOnDelete();
            $table->string('title')->unique();
            $table->datetime('starts_at');
            $table->datetime('ends_at');
            $table->timestamps();
        });

        Schema::create('round_songs', function (Blueprint $table)
        {
            $table->id();
            $table->foreignId('round_id')->constrained('rounds', 'id')->cascadeOnDelete();
            $table->foreignId('song_id')->constrained('songs', 'id')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['round_id', 'song_id']);
        });

        Schema::create('round_outcomes', function (Blueprint $table)
        {
            $table->id();
            $table->foreignId('round_id')->constrained('rounds', 'id')->cascadeOnDelete();
            $table->foreignId('song_id')->constrained('songs', 'id')->cascadeOnDelete();
            $table->unsignedInteger('first_votes')->default(0);
            $table->unsignedInteger('second_votes')->default(0);
            $table->unsignedInteger('third_votes')->default(0);
            $table->boolean('was_manual')->default(false)
                                         ->comment('True in the case where there were no public votes.');
            $table->timestamps();

            $table->unique(['round_id', 'song_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('round_outcomes');
        Schema::dropIfExists('round_songs');
        Schema::dropIfExists('rounds');
        Schema::dropIfExists('stages');
    }
};
