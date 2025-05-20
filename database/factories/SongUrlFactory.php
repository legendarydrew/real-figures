<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SongUrl>
 */
class SongUrlFactory extends Factory
{

    public function definition(): array
    {
        // https://github.com/aalaap/faker-youtube
        $this->faker->addProvider(new \Faker\Provider\Youtube($this->faker));
        return [
            'url' => $this->faker->youtubeUri(),
        ];
    }
}
