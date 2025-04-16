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
        Schema::create('acts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('act_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('act_id')->references('id')->on('acts')->onDelete('cascade');
            $table->text('description');
            $table->timestamps();
        });

        Schema::create('act_pictures', function (Blueprint $table)
        {
            $table->id();
            $table->foreignId('act_id')->constrained('acts')->cascadeOnDelete();
            $table->text('image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('act_pictures');
        Schema::dropIfExists('act_profiles');
        Schema::dropIfExists('acts');
    }
};
