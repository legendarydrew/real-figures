<?php

namespace Tests\Unit\Song;

use App\Models\Act;
use App\Models\Song;
use App\Models\SongUrl;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class LatestVersionTest extends TestCase
{
    use DatabaseMigrations;

    private Song $song;

    protected function setUp(): void
    {
        parent::setUp();
        $act = Act::factory()->withSong()->createOne();
        $this->song = $act->songs->first();
    }

    public function test_no_urls()
    {
        self::assertNull($this->song->latestVersion());
    }

    public function test_one_url() {
        $url = SongUrl::factory()->for($this->song)->createOne();
        self::assertInstanceOf(SongUrl::class, $this->song->latestVersion());
        self::assertEquals($url->url, $this->song->latestVersion()->url);
    }

    public function test_many_urls() {
        $urls = SongUrl::factory(7)->for($this->song)->create();
        $urls->sortByDesc('created_at');
        self::assertInstanceOf(SongUrl::class, $this->song->latestVersion());
        self::assertEquals($urls->first()->url, $this->song->latestVersion()->url);

    }
}
