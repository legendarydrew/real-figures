<?php

namespace Tests\Feature\Controllers\API;

use App\Models\Round;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class DumbrickTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = '/api/dumbrick';

    private array $payload;

    private Round        $round;
    private UploadedFile $file;


    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');

        $stage       = Stage::factory()->createOne();
        $this->round = Round::factory()->for($stage)->started()->withSongs(4)->createOne();
    }

    protected function createDataFile(array $contents): void
    {
        // Create a real temporary text file with specific content
        $tmpPath = sys_get_temp_dir() . '/votes-' . uniqid() . '.dat';
        file_put_contents($tmpPath, implode("\n", $contents));

        $this->file = new UploadedFile(
            $tmpPath,
            'votes.dat',
            'text/plain',
            null,
            true
        );
    }

    public function test_as_guest(): void
    {
        $this->createDataFile(['ABC']);
        $response = $this->postJson(self::ENDPOINT, ['data' => $this->file]);
        $response->assertUnauthorized();
    }

    public function test_as_user(): void
    {
        $this->createDataFile(['ABC']);
        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, ['data' => $this->file]);
        $response->assertCreated();
    }

    public function test_invalid_file(): void
    {
        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT);
        $response->assertUnprocessable();

        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, ['file' => fake()->image()]);
        $response->assertUnprocessable();
    }

    public function test_creates_votes(): void
    {
        $this->createDataFile([
            'ABC',
            'BCD',
            'CDA',
            'DAB'
        ]);
        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, ['data' => $this->file]);
        $response->assertCreated();
        $response->assertJsonPath('votes', 4);
    }

    public function test_no_votes(): void
    {
        $this->createDataFile([]);
        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, ['data' => $this->file]);
        $response->assertUnprocessable(); // empty file = no file, apparently.
    }

    public function test_more_choices_than_songs(): void
    {
        $this->createDataFile([
            'ABC',
            'BCD',
            'CDE',
            'DEF',
            'EFA',
            'FAB',
        ]);
        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, ['data' => $this->file]);
        $response->assertCreated();
        $response->assertJsonPath('votes', 6);
    }

    public function test_more_than_three_choices(): void
    {
        $this->createDataFile([
            'ABCD',
            'BCDA',
            'CDAB',
            'DABC',
        ]);
        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, ['data' => $this->file]);
        $response->assertCreated();
        $response->assertJsonPath('votes', 4);
    }

    public function test_partial_choices(): void
    {
        $this->createDataFile([
            'AB',
            'BC',
            'D',
            'BD',
            'A',
        ]);
        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, ['data' => $this->file]);
        $response->assertCreated();
        $response->assertJsonPath('votes', 5);
    }

    public function test_empty_choices(): void
    {
        $this->createDataFile([
            'ABC',
            '',
            'DBA',
            '',
            'CA',
        ]);
        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, ['data' => $this->file]);
        $response->assertCreated();
        $response->assertJsonPath('votes', 3);
    }

    public function test_no_current_round(): void
    {
        $this->round->delete();
        $this->createDataFile(['ABC']);
        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, ['data' => $this->file]);
        $response->assertBadRequest();
    }


}
