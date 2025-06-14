<?php

namespace Tests\Feature\Controllers\API\Act\Meta;

use App\Models\Act;
use App\Models\ActMetaMember;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class MembersTest extends TestCase
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
                'members' => []
            ]
        ];
    }

    public function test_adds_meta_members()
    {
        $this->payload['meta'] = [
            'members' => [
                ['name' => 'Max Power', 'role' => 'Bad Boy'],
                ['name' => 'Jess Chillin', 'role' => 'Bad Girl'],
            ]
        ];
        $this->actingAs($this->user)->patchJson(sprintf(self::ENDPOINT, $this->act->id), $this->payload);

        $this->act->refresh();
        self::assertCount(count($this->payload['meta']['members']), $this->act->members);
    }

    public function test_replace_meta_members()
    {
        $this->act->members()->createMany([
            ['name' => 'Max Power', 'role' => 'Bad Boy'],
            ['name' => 'Jess Chillin', 'role' => 'Bad Girl'],
        ]);

        $this->payload['meta'] = [
            'members' => [
                ['name' => 'Phil McCracken', 'role' => 'Owner'],
            ]
        ];

        $this->actingAs($this->user)->patchJson(sprintf(self::ENDPOINT, $this->act->id), $this->payload);

        $this->act->refresh();
        self::assertCount(count($this->payload['meta']['members']), $this->act->members);
    }

    public function test_preserve_meta_members()
    {
        $this->act->members()->createMany([
            ['name' => 'Max Power', 'role' => 'Bad Boy'],
            ['name' => 'Jess Chillin', 'role' => 'Bad Girl'],
        ]);

        $this->act->refresh();

        $this->payload['meta'] = [
            'members' => [
                ...$this->act->members->map(fn(ActMetaMember $member) => [
                    'id'   => $member->id,
                    'name' => $member->name,
                    'role' => $member->role
                ]),
                ['name' => 'Phil McCracken', 'role' => 'Owner'],
            ]
        ];
        $this->actingAs($this->user)->patchJson(sprintf(self::ENDPOINT, $this->act->id), $this->payload);

        $this->act->refresh();
        self::assertCount(count($this->payload['meta']['members']), $this->act->members);
    }

}
