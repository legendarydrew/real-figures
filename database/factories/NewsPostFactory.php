<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NewsPost>
 */
class NewsPostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        fake()->addProvider(new \DavidBadura\FakerMarkdownGenerator\FakerProvider(fake()));

        return [
            'title'        => fake()->sentence(),
            'content'      => fake()->markdown(),
            'published_at' => fake()->boolean(30) ? fake()->dateTimeThisYear() : null,
        ];
    }

    public function published(): NewsPostFactory
    {
        return $this->state([
            'published_at' => fake()->dateTimeThisMonth
        ]);
    }

    public function unpublished(): NewsPostFactory
    {
        return $this->state([
            'published_at' => null
        ]);
    }
}
