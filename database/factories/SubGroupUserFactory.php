<?php

namespace Database\Factories;

use App\Models\SubGroup;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubGroupUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'sub_group_id' => SubGroup::inRandomOrder()->first()->id,
        ];
    }
}
