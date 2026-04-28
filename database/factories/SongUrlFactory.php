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
        try {
            // https://github.com/aalaap/faker-youtube
            fake()->addProvider(new \Faker\Provider\Youtube(fake()));

            return [
                'url' => fake()->youtubeUri(),
            ];
        } catch (\Throwable $err) {
            return [
                'url' => fake()->url(),
            ];
        }
    }
}
