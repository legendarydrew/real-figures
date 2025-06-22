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
        Schema::create('genres', function (Blueprint $table)
        {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::table('act_meta_genres', function (Blueprint $table)
        {
            $table->foreignId('genre_id')->nullable()->after('act_id')->constrained('genres');
            $table->unique(['act_id', 'genre_id']);
            $table->dropUnique(['act_id', 'genre']);
            $table->dropColumn('genre');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('act_meta_genres', function (Blueprint $table)
        {
            $table->dropUnique(['act_id', 'genre_id']);
            if ('sqlite' !== config('database.default'))
            {
                $table->dropForeign('genre_id');
            }
            $table->string('genre')->after('act_id')->nullable();
            $table->unique(['act_id', 'genre']);
        });

        if ('sqlite' !== config('database.default'))
        {
            Schema::dropIfExists('genres');
        }
    }
};
