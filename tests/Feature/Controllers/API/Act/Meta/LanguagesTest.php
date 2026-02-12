<?php

namespace Tests\Feature\Controllers\API\Act\Meta;

use App\Models\Act;
use App\Models\ActMetaLanguage;
use App\Models\Language;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class LanguagesTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = '/api/acts/%u';

    private Act   $act;
    private array $payload;

    protected function setUp(): void
    {
        parent::setUp();

        $this->act     = Act::factory()->createOne();
        $this->payload = [
            'name' => fake()->name,
            'meta' => [
                'languages' => fake()->randomElements(Language::pluck('code')->toArray(), 3)
            ]
        ];
    }

    public function test_adds_meta_languages()
    {
        $this->actingAs($this->user)->patchJson(sprintf(self::ENDPOINT, $this->act->id), $this->payload);

        $this->act->refresh();
        self::assertCount(count($this->payload['meta']['languages']), $this->act->languages);

        $saved_language_codes = $this->act->languages->pluck('code')->toArray();
        foreach ($this->payload['meta']['languages'] as $language_code)
        {
            self::assertContains($language_code, $saved_language_codes);
        }
    }

    public function test_replace_meta_languages()
    {
        $language_ids = fake()->randomElements(Language::pluck('id')->toArray(), 3);
        foreach ($language_ids as $language_id)
        {
            ActMetaLanguage::create([
                'act_id'      => $this->act->id,
                'language_id' => $language_id
            ]);
        }

        $this->actingAs($this->user)->patchJson(sprintf(self::ENDPOINT, $this->act->id), $this->payload);

        $this->act->refresh();
        self::assertCount(count($this->payload['meta']['languages']), $this->act->languages);

        $saved_language_codes = $this->act->languages->pluck('code')->toArray();
        foreach ($this->payload['meta']['languages'] as $language_code)
        {
            self::assertContains($language_code, $saved_language_codes);
        }
    }

    public function test_preserve_meta_languages()
    {
        $language_ids = fake()->randomElements(Language::pluck('id')->toArray(), 3);
        foreach ($language_ids as $language_id)
        {
            ActMetaLanguage::create([
                'act_id'      => $this->act->id,
                'language_id' => $language_id
            ]);
        }

        $new_language_codes                 = fake()->randomElements(Language::pluck('code')->toArray(), 2);
        $this->payload['meta']['languages'] = array_unique(array_merge($this->payload['meta']['languages'], $new_language_codes));
        $this->actingAs($this->user)->patchJson(sprintf(self::ENDPOINT, $this->act->id), $this->payload);

        $this->act->refresh();
        self::assertCount(count($this->payload['meta']['languages']), $this->act->languages);

        $saved_language_codes = $this->act->languages->pluck('code')->toArray();
        foreach ($this->payload['meta']['languages'] as $language_code)
        {
            self::assertContains($language_code, $saved_language_codes);
        }
    }

}
