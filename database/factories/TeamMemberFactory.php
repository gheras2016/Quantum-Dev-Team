<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TeamMember>
 */
class TeamMemberFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'role' => $this->faker->jobTitle(),
            'bio' => $this->faker->sentence(12),
            'skills' => $this->faker->randomElements(['Laravel', 'Vue', 'React', 'Flutter', 'PHP', 'MySQL'], 3),
            'order' => $this->faker->numberBetween(0, 10),
            'is_active' => true,
            'status' => 'published',
        ];
    }
}
