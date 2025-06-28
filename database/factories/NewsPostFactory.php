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
            'title'   => $this->faker->sentence(),
            'content' => $this->faker->markdown()
        ];
    }

    public function published(): NewsPostFactory
    {
        return $this->state([
            'published_at' => fake()->dateTimeThisMonth
        ]);
    }
}
