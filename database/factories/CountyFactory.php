<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\Breed;
use App\Models\Genre;
use App\Models\TypeAlerts;
use Illuminate\Database\Eloquent\Factories\Factory;

class CountyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $ptBrFaker = \Faker\Factory::create('pt_BR');
        
        return [
            'codigo_ibge' => $this->faker->randomNumber(6),
            'name' =>  $ptBrFaker->city, 
            'state_id' => $this->faker->numberBetween(1, 27),        
        ];
    }
}
