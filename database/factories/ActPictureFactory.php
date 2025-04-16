<?php

namespace Database\Factories;

use App\Models\ActPicture;
use Illuminate\Database\Eloquent\Factories\Factory;
use Intervention\Image\Decoders\Base64ImageDecoder;
use Intervention\Image\Laravel\Facades\Image;

/**
 * @extends Factory<ActPicture>
 */
class ActPictureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $this->faker->addProvider(new \Smknstd\FakerPicsumImages\FakerPicsumImagesProvider($this->faker));
        return [
            'image' => Image::read($this->faker->image)->encode()->toDataUri()
        ];
    }
}
