<?php

namespace Database\Seeders;

use App\Models\Accession;
use App\Models\County;
use App\Models\Occupation;
use App\Models\User;
use Illuminate\Database\Seeder;

class AccessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userPrefeito = User::where('occupation_id', Occupation::where('name', Occupation::OCCUPATIONS[Occupation::PREFEITO])->first()->id)->first();
        $userGestorPolitico = User::where('occupation_id', Occupation::where('name', Occupation::OCCUPATIONS[Occupation::GESTOR_POLITICO])->first()->id)->first();
        $accessions = [
            [
                'county_id' => $userPrefeito->county_id, //County::inRandomOrder()->first()->id,
                'prefeito_id' => $userPrefeito->id, //User::inRandomOrder()->first()->id,
                'gestor_politico_id' => $userGestorPolitico->id,
                'status_prefeito' => Accession::STATUS['PENDENTE'],
                'status_gestor_politico' => Accession::STATUS['PENDENTE'],
                'status' => Accession::STATUS['PENDENTE']
            ],            
        ];

        foreach ($accessions as $value) {
            Accession::create($value);
        }
    }
}
