<?php

namespace Database\Factories;

use App\Models\SubGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnderSubGroupFactory extends Factory
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
            'name' => $ptBrFaker->word,
            'logradouro' => $ptBrFaker->streetName,
            'endereco' => $ptBrFaker->streetAddress,
            'bairro' => $ptBrFaker->name,
            'latitude' => $ptBrFaker->latitude,
            'longitude' => $ptBrFaker->longitude,
            'sub_group_id' => SubGroup::inRandomOrder()->first()->id,
        ];
    }
}
