<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Faq>
 */
class FaqFactory extends Factory
{
    public function definition(): array
    {
        return [
            'question' => ['en' => $this->faker->sentence().'?', 'ar' => $this->faker->sentence().'؟'],
            'answer' => ['en' => $this->faker->paragraph(), 'ar' => $this->faker->paragraph()],
            'order' => $this->faker->numberBetween(0, 10),
            'is_active' => true,
        ];
    }
}
