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
        Schema::create('songs', function (Blueprint $table)
        {
            $table->id();
            $table->foreignId('act_id')->constrained('acts')->cascadeOnDelete();
            $table->string('title');
            $table->string('language', 2)->default('en');
            $table->timestamps();
        });

        Schema::create('song_urls', function (Blueprint $table)
        {
            $table->id();
            $table->foreignId('song_id')->constrained('songs')->cascadeOnDelete();
            $table->string('url');
            $table->timestamps();
        });

        Schema::create('song_plays', function (Blueprint $table)
        {
            $table->id();
            $table->foreignId('song_id')->constrained('songs')->cascadeOnDelete();
            $table->date('played_on');
            $table->unsignedBigInteger('play_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('song_plays');
        Schema::dropIfExists('song_urls');
        Schema::dropIfExists('songs');
    }
};
