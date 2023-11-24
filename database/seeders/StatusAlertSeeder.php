<?php

namespace Database\Seeders;

use App\Models\Record;
use App\Models\StatusAlert;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusAlertSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create a status_alerts record
        $status = [
            'visita',
            'alerta',
            'análise técnica',
            'encaminhamento',
            'sala de vacina',
            'concluído',
            'encerrado'
        ];
        foreach ($status as $key => $value) {         
            StatusAlert::firstOrCreate([
                'name' => $value,
            ],
            [
                'name' => $value,
            ]);
        }              
    }
}
