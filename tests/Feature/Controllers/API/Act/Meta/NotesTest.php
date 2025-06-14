<?php

namespace Controllers\API\Act\Meta;

use App\Models\Act;
use App\Models\ActMetaNote;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class NotesTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = '/api/acts/%u';

    private Act   $act;
    private array $payload;

    protected function setUp(): void
    {
        parent::setUp();

        $this->act     = Act::factory()->withPicture()->createOne();
        $this->payload = [
            'name' => fake()->name,
            'meta' => [
                'notes' => []
            ]
        ];
    }

    public function test_adds_meta_notes()
    {
        $this->payload['meta']['notes'] = [
            ['note' => fake()->sentence()],
            ['note' => fake()->sentence()],
        ];
        $this->actingAs($this->user)->patchJson(sprintf(self::ENDPOINT, $this->act->id), $this->payload);

        $this->act->refresh();
        self::assertCount(count($this->payload['meta']['notes']), $this->act->notes);
    }

    public function test_replace_meta_notes()
    {
        $this->act->notes()->createMany([
            ['note' => fake()->sentence()],
            ['note' => fake()->sentence()],
        ]);

        $this->payload['meta']['notes'] = [
            ['note' => fake()->sentence()]
        ];

        $this->actingAs($this->user)->patchJson(sprintf(self::ENDPOINT, $this->act->id), $this->payload);

        $this->act->refresh();
        self::assertCount(count($this->payload['meta']['notes']), $this->act->notes);
    }

    public function test_preserve_meta_notes()
    {
        $this->act->notes()->createMany([
            ['note' => fake()->sentence()],
            ['note' => fake()->sentence()]
        ]);

        $this->act->refresh();

        $this->payload['meta']['notes'] = [
            ...$this->act->notes->map(fn(ActMetaNote $note) => [
                'id'   => $note->id,
                'note' => $note->note
            ]),
            ['note' => fake()->sentence()],
        ];
        $this->actingAs($this->user)->patchJson(sprintf(self::ENDPOINT, $this->act->id), $this->payload);

        $this->act->refresh();
        self::assertCount(count($this->payload['meta']['notes']), $this->act->notes);
    }

}
