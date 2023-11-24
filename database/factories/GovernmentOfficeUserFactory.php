<?php

namespace Database\Factories;

use App\Models\GovernmentOffice;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class GovernmentOfficeUserFactory extends Factory
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
            'government_office_id' => GovernmentOffice::inRandomOrder()->first()->id,
        ];
    }
}
