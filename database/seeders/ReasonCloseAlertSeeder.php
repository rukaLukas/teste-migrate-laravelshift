<?php

namespace Database\Seeders;

use App\Models\ReasonCloseAlert;
use App\Models\TargetPublic;
use Illuminate\Database\Seeder;

class ReasonCloseAlertSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = array('Óbito da criança', 'Criança não encontrada', 'Mudança de estados ou município', 'Outro');
        foreach ($types as $key => $value) {
            ReasonCloseAlert::firstOrCreate([
                'description' => $value,
            ],
            [
                'description' => $value,
            ]);
        }
    }
}
