<?php

namespace Tests\Feature\ActImage;

use App\Facades\ActImageFacade;
use App\Models\Act;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Smknstd\FakerPicsumImages\FakerPicsumImagesProvider;
use Tests\TestCase;

class RenameTest extends TestCase
{
    use DatabaseMigrations;

    private Act $act;

    protected function setUp(): void
    {
        parent::setUp();
        fake()->addProvider(new FakerPicsumImagesProvider(fake()));

        $this->act = Act::factory()->createOne();
        $image = fake()->image();
        ActImageFacade::create($this->act, $image);

        $path = ActImageFacade::path($this->act);
        $this->assertFileExists($path);

        Act::bootHasEvents();
    }

    public function test_renames_existing_image_from_name_change(): void
    {
        $this->act->update([
            'name' => fake()->name,
        ]);
        ActImageFacade::rename($this->act);
        $this->act->refresh();

        $path = ActImageFacade::path($this->act);
        $this->assertFileExists($path);
        @unlink($path);
    }

    public function test_renames_existing_image_from_subtitle_change(): void
    {
        $this->act->update([
            'subtitle' => fake()->sentence,
        ]);
        ActImageFacade::rename($this->act);

        $path = ActImageFacade::path($this->act);
        $this->assertFileExists($path);
        @unlink($path);
    }

    public function test_preserve_existing_image_with_same_slug(): void
    {
        ActImageFacade::rename($this->act);

        $path = ActImageFacade::path($this->act);
        $this->assertFileExists($path);
        @unlink($path);
    }

    public function test_missing_image(): void
    {
        $path = ActImageFacade::path($this->act);
        $this->assertFileExists($path);
        @unlink($path);

        //        $this->expectException(FileNotFoundException::class);
        ActImageFacade::rename($this->act);

        $path = ActImageFacade::path($this->act);
        $this->assertFileDoesNotExist($path);
    }
}
