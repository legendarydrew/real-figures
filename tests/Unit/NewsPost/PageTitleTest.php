<?php

namespace Tests\Unit\NewsPost;

use App\Models\NewsPost;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

final class PageTitleTest extends TestCase
{
    use DatabaseMigrations;

    private int $max_title_length = 50;

    final public function test_short_title(): void
    {
        $post = NewsPost::factory()->createOne([
            'title' => 'A Short Title'
        ]);

        self::assertLessThanOrEqual($this->max_title_length, strlen($post->title));
        self::assertLessThanOrEqual($this->max_title_length, strlen($post->page_title));
        self::assertStringEndsNotWith('...', $post->page_title);
    }

    final public function test_long_title(): void
    {
        $post = NewsPost::factory()->createOne([
            'title' => 'This is a long title that should be longer than the configured number of characters.'
        ]);

        self::assertGreaterThanOrEqual($this->max_title_length, strlen($post->title));
        self::assertLessThanOrEqual($this->max_title_length, strlen($post->page_title));
        self::assertStringEndsWith('...', $post->page_title);
    }

    protected function setUp(): void
    {
        parent::setUp();
        config()->set('contest.news.max_title_length', $this->max_title_length);
    }
}
