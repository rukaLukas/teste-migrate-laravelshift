<?php

namespace Database\Factories;

use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubGroupFactory extends Factory
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
            'name' => $ptBrFaker->word(),
            'group_id' => Group::inRandomOrder()->first()->id,
        ];
    }
}
