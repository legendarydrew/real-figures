<?php

use App\Enums\NewsPostType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('news_posts', function (Blueprint $table)
        {
            $table->id();
            $table->string('title');
            $table->string('type', 8)->index()->default(NewsPostType::CUSTOM_POST_TYPE);
            $table->unsignedInteger('reference_id')->index()->nullable();
            $table->text('content')->comment('Markdown content.');
            $table->dateTime('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_posts');
    }
};
