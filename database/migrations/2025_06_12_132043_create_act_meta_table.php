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
        Schema::create('act_meta_genres', function (Blueprint $table)
        {
            $table->id();
            $table->foreignId('act_id')->references('id')->on('acts')->onDelete('cascade');
            $table->string('genre');
            $table->timestamps();

            $table->unique(['act_id', 'genre']);
        });

        Schema::create('act_meta_members', function (Blueprint $table)
        {
            $table->id();
            $table->foreignId('act_id')->references('id')->on('acts')->onDelete('cascade');
            $table->string('name');
            $table->string('role');
            $table->timestamps();
        });

        Schema::create('act_meta_traits', function (Blueprint $table)
        {
            $table->id();
            $table->foreignId('act_id')->references('id')->on('acts')->onDelete('cascade');
            $table->string('trait');
            $table->timestamps();
        });

        Schema::create('act_meta_languages', function (Blueprint $table)
        {
            $table->id();
            $table->foreignId('act_id')->references('id')->on('acts')->onDelete('cascade');
            $table->string('language', 2);
            $table->timestamps();

            $table->unique(['act_id', 'language']);
        });

        Schema::create('act_meta_notes', function (Blueprint $table)
        {
            $table->id();
            $table->foreignId('act_id')->references('id')->on('acts')->onDelete('cascade');
            $table->string('note');
            $table->timestamps();
        });

        Schema::table('act_profiles', function (Blueprint $table)
        {
            $table->boolean('is_fan_favourite')->default(false)->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('act_meta_notes');
        Schema::dropIfExists('act_meta_languages');
        Schema::dropIfExists('act_meta_traits');
        Schema::dropIfExists('act_meta_members');
        Schema::dropIfExists('act_meta_genres');
    }
};
