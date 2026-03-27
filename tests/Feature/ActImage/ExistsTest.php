<?php

namespace Tests\Feature\ActImage;

use App\Facades\ActImageFacade;
use App\Models\Act;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Smknstd\FakerPicsumImages\FakerPicsumImagesProvider;
use Tests\TestCase;

final class ExistsTest extends TestCase
{
    use DatabaseMigrations;

    private Act $act;

    protected function setUp(): void
    {
        parent::setUp();
        fake()->addProvider(new FakerPicsumImagesProvider(fake()));

        $this->act = Act::factory()->createOne();
    }

    public function test_existing_image(): void
    {
        $path = ActImageFacade::path($this->act);
        touch($path);

        self::assertTrue(ActImageFacade::exists($this->act));
    }

    public function test_non_existent_image(): void
    {
        self::assertFalse(ActImageFacade::exists($this->act));
    }
}
