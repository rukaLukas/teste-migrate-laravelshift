<?php

namespace Database\Factories;

use App\Models\State;
use App\Helper\Number;
use App\Models\County;
use App\Models\Region;
use App\Models\Profile;
use App\Models\Pronoun;
use App\Models\Occupation;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $ptBrFaker = \Faker\Factory::create('pt_BR');
        $minAge = Carbon::now()->subYears(18)->format('Y-m-d');
        return [
            'pronoun_id' => Pronoun::inRandomOrder()->first()->id,
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('123456'), // password
            'remember_token' => Str::random(10),
            'profile_id' => Profile::inRandomOrder()->first()->id,
            'cpf' => Number::onlyNumbers($ptBrFaker->cpf),
            'birthdate' => $this->faker->date('Y-m-d', $minAge),//$this->faker->date('d/m/Y', $minAge),
            'occupation_id' => Occupation::inRandomOrder()->first()->id,
            'office_phone' => sprintf('(%s) %s', $ptBrFaker->areaCode, $ptBrFaker->landline),
            'cell_phone' => sprintf('(%s) %s', $ptBrFaker->areaCode, $ptBrFaker->landline),
            'county_id' => County::latest()->first()->id
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
