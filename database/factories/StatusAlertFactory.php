<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\Breed;
use App\Models\Genre;
use App\Helper\Number;
use App\Models\TypeAlerts;
use Illuminate\Database\Eloquent\Factories\Factory;

class StatusAlertFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {        
        return [
            // create a faker name
            'name' => $this->faker->name           
        ];
    }
}
