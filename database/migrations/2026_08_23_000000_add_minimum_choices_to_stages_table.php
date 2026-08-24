<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('stages', function (Blueprint $table)
        {
            $table->unsignedTinyInteger('minimum_choices')
                  ->default(1)
                  ->after('golden_buzzer_perks');
        });
    }

    public function down(): void
    {
        Schema::table('stages', function (Blueprint $table)
        {
            $table->dropColumn('minimum_choices');
        });
    }
};
