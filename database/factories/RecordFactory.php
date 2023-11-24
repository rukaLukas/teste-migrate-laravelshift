<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\Breed;
use App\Models\Genre;
use App\Helper\Number;
use App\Models\TypeAlerts;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecordFactory extends Factory
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
            'cpf' => Number::onlyNumbers($ptBrFaker->cpf),
            'suscard' => Number::onlyNumbers($this->faker->numerify('###############')),            
        ];
    }
}
