<?php

namespace Database\Seeders;

use App\Helper\Number;
use App\Models\User;
use App\Models\County;
use App\Models\Profile;
use App\Models\Occupation;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Models\GovernmentAgency;
use App\Models\Pronoun;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('pt_BR');
        User::create([
            'name' => 'Admin Gestor Nacional',
            'email' => 'admin@user.com',
            'password' => Hash::make(123456),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'cpf' => Number::onlyNumbers($faker->cpf),
            'office_phone' => sprintf('(%s) %s', $faker->areaCode, $faker->landline),
            'position' => $faker->title,
            'profile_id' => Profile::inRandomOrder()->first()->id,
            // 'government_agency_id' => GovernmentAgency::inRandomOrder()->first()->id,
            'occupation_id' => Occupation::GESTOR_NACIONAL,
            'county_id' => County::inRandomOrder()->first()->id,
        ]);


        $occupations = Occupation::all();
        $countyId = County::inRandomOrder()->first()->id;
        foreach ($occupations as $occupation) {
            $countyId = County::inRandomOrder()->first()->id;
            if ($occupation->id === Occupation::COORDENADOR_OPERACIONAL_SAUDE) {
                $countyId = County::latestSeederId();
            }

            User::create([
                'name' => $this->makeName($occupation->name),
                'email' => $this->makeEmail($occupation->name),
                'password' => Hash::make(123456),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
                'cpf' => Number::onlyNumbers($faker->cpf),
                'office_phone' => sprintf('(%s) %s', $faker->areaCode, $faker->landline),
                'position' => $faker->title,
                'profile_id' => Profile::inRandomOrder()->first()->id,
                'occupation_id' => $occupation->id,
                'pronoun_id' => Pronoun::inRandomOrder()->first()->id,
                'county_id' => $countyId,//5570
            ]);
        }

        User::factory()->count(5)->create();
    }

    private function makeName($name)
    {
        $name = removeAcentos($name);
        $name = str_replace('(a)', '', $name);
        return ucwords($name);
    }

    private function makeEmail($email)
    {
        $email = removeAcentos($email);
        $email = str_replace('(a)', '', $email);
        $email = str_replace(
            ' ', '_', preg_replace('/[^a-zA-Z0-9_.]/', ' ', strtolower($email))
        );
        return $email . '@user.com';
    }
}
