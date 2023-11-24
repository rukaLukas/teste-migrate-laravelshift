<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\Breed;
use App\Models\Genre;
use App\Models\TypeAlerts;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class OccupationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {        
        return [
            'name' => $this->faker->name(),
            'uuid' => Str::uuid(),
        ];        
    }
}
