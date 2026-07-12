<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Testimonial>
 */
class TestimonialFactory extends Factory
{
    public function definition(): array
    {
        return [
            'author_name' => $this->faker->name(),
            'author_title' => $this->faker->jobTitle(),
            'author_company' => $this->faker->company(),
            'content' => ['en' => $this->faker->sentence(12), 'ar' => $this->faker->sentence(12)],
            'rating' => $this->faker->numberBetween(4, 5),
            'order' => $this->faker->numberBetween(0, 10),
            'is_active' => true,
        ];
    }
}
