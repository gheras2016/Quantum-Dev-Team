<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    public function definition(): array
    {
        $title = $this->faker->unique()->bs();

        return [
            'slug' => Str::slug($title).'-'.$this->faker->unique()->numberBetween(1, 99999),
            'title' => ['en' => $title, 'ar' => $title],
            'description' => ['en' => $this->faker->sentence(), 'ar' => $this->faker->sentence()],
            'order' => $this->faker->numberBetween(0, 10),
            'is_active' => true,
            'status' => 'published',
        ];
    }
}
