<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\Breed;
use App\Models\Genre;
use App\Models\TypeAlerts;
use Illuminate\Database\Eloquent\Factories\Factory;

class GenreFactory extends Factory
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
        ];
    }
}
