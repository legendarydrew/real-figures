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
        $this->faker->addProvider(new \DavidBadura\FakerMarkdownGenerator\FakerProvider($this->faker));

        return [
            'title'        => $this->faker->sentence(),
            'content'      => $this->faker->markdown(),
            'published_at' => $this->faker->boolean(30) ? $this->faker->dateTimeThisYear() : null,
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
