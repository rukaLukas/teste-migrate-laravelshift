<?php

namespace Database\Seeders;

use App\Models\TypeReasonDelayVaccine;
use Illuminate\Database\Seeder;

class TypeReasonDelayVaccineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = array('Atraso Vacinal', 'Não Vacinada', 'Não ter resolvido o atraso vacinal na sala de vacina', 'Não ter a carteirinha');
        foreach ($types as $key => $value) {
            TypeReasonDelayVaccine::updateOrCreate(
                [
                    'id' => ++$key                                              
                ],
                [
                    'description' => $value
                ]
            );     
        }
    }
}
