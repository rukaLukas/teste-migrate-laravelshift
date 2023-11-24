<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ReasonsVaccineDelayFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'detail' => $this->faker->word(),

        ];
    }
}
