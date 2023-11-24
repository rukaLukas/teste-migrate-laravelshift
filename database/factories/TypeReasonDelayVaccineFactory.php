<?php

namespace Database\Factories;

use App\Models\UnderSubGroup;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TypeReasonDelayVaccineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uuid' => $this->faker->uuid(),
            'description' => $this->faker->name,            
        ];
    }
}
