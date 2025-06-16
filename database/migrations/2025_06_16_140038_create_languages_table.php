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
        Schema::create('languages', function (Blueprint $table)
        {
            $table->id();
            $table->string('code', 2)->unique();
            $table->string('name');
            $table->timestamps();
        });

        // Update tables that use language codes.
        Schema::table('songs', function (Blueprint $table)
        {
            $table->foreignId('language_id')->after('title')->constrained('languages')->cascadeOnDelete();
            $table->dropColumn('language');
        });
        Schema::table('act_meta_languages', function (Blueprint $table)
        {
            $table->foreignId('language_id')->after('act_id')->constrained('languages')->cascadeOnDelete();
            $table->unique(['act_id', 'language_id']);
            $table->dropUnique(['act_id', 'language']);
            $table->dropColumn('language');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('act_meta_languages', function (Blueprint $table)
        {
            $table->string('language', 2)->after('act_id');
            $table->dropColumn('language_id');
        });
        Schema::table('songs', function (Blueprint $table)
        {
            $table->string('language', 2)->after('title');
            $table->dropColumn('language_id');
        });
        Schema::dropIfExists('languages');
    }
};
