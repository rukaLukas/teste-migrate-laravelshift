<?php

namespace Database\Seeders;

use App\Models\TypeStatusVaccination;
use Illuminate\Database\Seeder;

class TypeStatusVaccinationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = array('Vacinação em dia', 'Atraso Vacinal', 'Não Vacinada', 'Sem carteirinha');
        foreach ($types as $key => $value) {
            TypeStatusVaccination::create([
                'name' => $value,
            ]);
        }
    }
}
