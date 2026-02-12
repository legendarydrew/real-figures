<?php

namespace Tests\Feature\ActImage;

use App\Facades\ActImageFacade;
use App\Models\Act;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Smknstd\FakerPicsumImages\FakerPicsumImagesProvider;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use DatabaseMigrations;

    private Act $act;

    protected function setUp(): void
    {
        parent::setUp();
        fake()->addProvider(new FakerPicsumImagesProvider(fake()));

        $this->act = Act::factory()->createOne();
    }

    public function test_removes_existing_image()
    {
        $path = ActImageFacade::path($this->act);
        touch($path);

        ActImageFacade::delete(act: $this->act);
        $this->assertFileDoesNotExist($path);
    }

    public function test_non_existent_image()
    {
        $path = ActImageFacade::path($this->act);

        ActImageFacade::delete(act: $this->act);
        $this->assertFileDoesNotExist($path);
    }

}
