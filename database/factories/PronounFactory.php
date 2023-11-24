<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PronounFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uuid' => $this->faker->uuid,
            'name' => $this->faker->randomElement(['Sr.', 'Srª.']),                     
        ];
    }
}
