<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(4);

        return [
            'slug' => Str::slug($title).'-'.$this->faker->unique()->numberBetween(1, 99999),
            'title' => ['en' => $title, 'ar' => $title],
            'excerpt' => ['en' => $this->faker->sentence(), 'ar' => $this->faker->sentence()],
            'body' => ['en' => $this->faker->paragraphs(3, true), 'ar' => $this->faker->paragraphs(3, true)],
            'status' => 'published',
            'featured' => $this->faker->boolean(20),
            'published_at' => now(),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => ['status' => 'draft', 'published_at' => null]);
    }
}
