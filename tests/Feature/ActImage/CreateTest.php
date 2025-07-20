<?php

namespace Tests\Feature\ActImage;

use App\Facades\ActImageFacade;
use App\Models\Act;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Smknstd\FakerPicsumImages\FakerPicsumImagesProvider;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use DatabaseMigrations;

    private Act $act;

    protected function setUp(): void
    {
        parent::setUp();
        fake()->addProvider(new FakerPicsumImagesProvider(fake()));

        $this->act = Act::factory()->createOne();
    }

    public function test_creates_new_image()
    {
        $image = fake()->image();
        ActImageFacade::create($this->act, $image);

        $path = ActImageFacade::path($this->act);

        $this->assertFileExists($path);
        @unlink($path);
    }

    public function test_resizes_image()
    {
        $image = fake()->image(width: 2000, height: 2000);
        ActImageFacade::create($this->act, $image);

        $path      = ActImageFacade::path($this->act);
        $new_image = \Intervention\Image\Laravel\Facades\Image::read($path);
        self::assertLessThanOrEqual(config('contest.images.resize')[0], $new_image->width());
        self::assertLessThanOrEqual(config('contest.images.resize')[1], $new_image->height());

        @unlink($path);
    }

    public function test_overwrites_image()
    {
        $path = ActImageFacade::path($this->act);

        ActImageFacade::create($this->act, fake()->image());
        $old_checksum = sha1_file($path);

        ActImageFacade::create($this->act, fake()->image());
        $new_checksum = sha1_file($path);

        self::assertNotEquals($old_checksum, $new_checksum);

        @unlink($path);
    }
}
