<?php

namespace Database\Seeders;

use App\Models\ReasonNotAppliedVaccine;
use Illuminate\Database\Seeder;

class ReasonNotAppliedVaccineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = array('Falta de vacina', 'Falta de profissional habilitado para vacinação', 'Outro (especifique)');
        foreach ($types as $key => $value) {
            ReasonNotAppliedVaccine::firstOrCreate([
                'description' => $value,
            ],
            [
                'description' => $value,
            ]);
        }
    }
}
