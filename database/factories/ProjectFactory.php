<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    public function definition(): array
    {
        $title = $this->faker->unique()->catchPhrase();

        return [
            'slug' => Str::slug($title).'-'.$this->faker->unique()->numberBetween(1, 99999),
            'title' => ['en' => $title, 'ar' => $title],
            'description' => ['en' => $this->faker->paragraph(), 'ar' => $this->faker->paragraph()],
            'client_name' => $this->faker->company(),
            'duration' => $this->faker->numberBetween(1, 12).' months',
            'progress' => $this->faker->numberBetween(0, 100),
            'status' => 'published',
            'featured' => $this->faker->boolean(30),
            'published_at' => now(),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => ['status' => 'draft', 'published_at' => null]);
    }

    public function featured(): static
    {
        return $this->state(fn () => ['featured' => true]);
    }
}
