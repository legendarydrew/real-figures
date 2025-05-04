<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscriber>
 */
class SubscriberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email'             => fake()->unique()->email(),
            'confirmation_code' => substr(fake()->uuid(), 0, 24),
            'confirmed'         => true
        ];
    }

    public function unconfirmed(): SubscriberFactory|Factory
    {
        return $this->state(fn() => ['confirmed' => false]);
    }

    public function confirmed(): SubscriberFactory|Factory
    {
        return $this->state(fn() => ['confirmed' => true]);
    }
}
