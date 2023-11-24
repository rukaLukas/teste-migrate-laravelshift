<?php

namespace Database\Factories;

use App\Models\UnderSubGroup;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnderSubGroupUserFactory extends Factory
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
            'under_sub_group_id' => UnderSubGroup::inRandomOrder()->first()->id,
        ];
    }
}
