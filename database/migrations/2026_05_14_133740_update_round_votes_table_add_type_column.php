<?php

use App\Enums\VoteType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('round_votes', function (Blueprint $table)
        {
            $table->enum('vote_type', VoteType::cases())
                  ->default(VoteType::ORGANIC->value)
                  ->after('round_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('round_votes', function (Blueprint $table)
        {
            $table->dropColumn('vote_type');
        });
    }
};
