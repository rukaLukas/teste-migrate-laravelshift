<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\Breed;
use App\Models\Genre;
use App\Models\TypeAlerts;
use Illuminate\Database\Eloquent\Factories\Factory;

class StateFactory extends Factory
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
            'name' => $ptBrFaker->state,
            'sigla' => $ptBrFaker->stateAbbr, 
            'region_id' => $this->faker->numberBetween(1, 5),
            'codigo_uf' => $this->faker->numberBetween(1, 53),                   
        ];
    }
}
