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
            $table->foreignId('language_id')->nullable()->after('title')->constrained('languages')->cascadeOnDelete();
            $table->dropColumn('language');
        });
        Schema::table('act_meta_languages', function (Blueprint $table)
        {
            $table->foreignId('language_id')->nullable()->after('act_id')->constrained('languages')->cascadeOnDelete();
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
            $table->dropUnique(['act_id', 'language_id']);
            if ('sqlite' !== config('database.default')) {
                $table->dropColumn('language_id');
            }
            $table->string('language', 2)->nullable()->after('act_id');
            $table->unique(['act_id', 'language']);
        });
        Schema::table('songs', function (Blueprint $table)
        {
            if ('sqlite' !== config('database.default')) {
                $table->dropColumn('language_id');
            }
            $table->string('language', 2)->nullable()->after('title');
        });
        Schema::dropIfExists('languages');
    }
};
