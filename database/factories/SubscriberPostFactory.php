<?php

namespace Database\Factories;

use App\Models\User;
use DavidBadura\FakerMarkdownGenerator\FakerProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubscriberPost>
 */
class SubscriberPostFactory extends Factory
{

    public function definition(): array
    {
        fake()->addProvider(FakerProvider::class);
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'title' => fake()->sentence,
            'sent_count' => fake()->boolean(10) ? 0 : fake()->numberBetween(1, 1000),
            'body' => fake()->markdown()
        ];
    }
}
