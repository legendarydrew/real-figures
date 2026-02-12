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
        Schema::dropIfExists('act_pictures');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('act_pictures', function (Blueprint $table)
        {
            $table->id();
            $table->foreignId('act_id')->constrained('acts')->cascadeOnDelete();
            $table->text('image');
            $table->timestamps();
        });
    }
};
