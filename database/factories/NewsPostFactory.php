<?php

namespace Database\Factories;

use App\Models\Act;
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

        // Random mentions.
        $acts = Act::inRandomOrder()->limit(3)->get();
        $content = fake()->markdown();
        $acts->each(function (Act $act) use (&$content) {
            if (fake()->boolean(30)) {
                $content .= "\n\n" . $act->name;
            }
        });

        return [
            'title'        => fake()->sentence(),
            'content'      => $content,
            'published_at' => fake()->boolean(30) ? fake()->dateTimeThisYear() : null,
        ];
    }

    public function published(): NewsPostFactory
    {
        return $this->state([
            'published_at' => fake()->dateTimeThisYear
        ]);
    }

    public function unpublished(): NewsPostFactory
    {
        return $this->state([
            'published_at' => null
        ]);
    }
}
