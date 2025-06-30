<?php

namespace Database\Factories;

use App\Models\ActPicture;
use Illuminate\Database\Eloquent\Factories\Factory;
use Intervention\Image\Laravel\Facades\Image;
use Smknstd\FakerPicsumImages\FakerPicsumImagesProvider;

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
        $this->faker->addProvider(new FakerPicsumImagesProvider($this->faker));
        $image = $this->faker->image;
        return [
            'image' => $image ? Image::read($image)->encode()->toDataUri() : null
        ];
    }
}
