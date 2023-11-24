<?php

namespace Database\Factories;

use App\Models\TargetPublic;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReasonDelayVaccineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'target_public_id' => TargetPublic::inRandomOrder()->first()->id,
            'description' => $this->faker->name(),
            'is_send_social_assistence' => $this->faker->boolean(),
            'to' => 'Atraso vacinal',
            'forwarding' => rand(0, 1) === 1 ? 'Assistência Social' : ''                        
        ];
    }
}
