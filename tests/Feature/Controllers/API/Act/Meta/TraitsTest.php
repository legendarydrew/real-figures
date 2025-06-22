<?php

namespace Controllers\API\Act\Meta;

use App\Models\Act;
use App\Models\ActMetaTrait;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class TraitsTest extends TestCase
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
                'traits' => []
            ]
        ];
    }

    public function test_adds_meta_traits()
    {
        $this->payload['meta']['traits'] = [
            ['trait' => fake()->words(2, true)],
            ['trait' => fake()->words(2, true)],
        ];
        $this->actingAs($this->user)->patchJson(sprintf(self::ENDPOINT, $this->act->id), $this->payload);

        $this->act->refresh();
        self::assertCount(count($this->payload['meta']['traits']), $this->act->traits);
        foreach ($this->payload['meta']['traits'] as $index => $trait)
        {
            self::assertEquals($trait['trait'], $this->act->traits[$index]->trait);
        }
    }

    public function test_replace_meta_traits()
    {
        $this->act->traits()->createMany([
            ['trait' => fake()->words(2, true)],
            ['trait' => fake()->words(2, true)],
        ]);

        $this->payload['meta']['traits'] = [
            ['trait' => fake()->words(2, true)]
        ];

        $this->actingAs($this->user)->patchJson(sprintf(self::ENDPOINT, $this->act->id), $this->payload);

        $this->act->refresh();
        self::assertCount(count($this->payload['meta']['traits']), $this->act->traits);
        foreach ($this->payload['meta']['traits'] as $index => $trait)
        {
            self::assertEquals($trait['trait'], $this->act->traits[$index]->trait);
        }
    }

    public function test_preserve_meta_traits()
    {
        $this->act->traits()->createMany([
            ['trait' => fake()->words(2, true)],
            ['trait' => fake()->words(2, true)]
        ]);

        $this->act->refresh();

        $this->payload['meta']['traits'] = [
            ...$this->act->traits->map(fn(ActMetaTrait $trait) => [
                'id'    => $trait->id,
                'trait' => $trait->trait
            ]),
            ['trait' => fake()->words(2, true)],
        ];
        $this->actingAs($this->user)->patchJson(sprintf(self::ENDPOINT, $this->act->id), $this->payload);

        $this->act->refresh();
        self::assertCount(count($this->payload['meta']['traits']), $this->act->traits);
        foreach ($this->payload['meta']['traits'] as $index => $trait)
        {
            self::assertEquals($trait['trait'], $this->act->traits[$index]->trait);
        }
    }

}
