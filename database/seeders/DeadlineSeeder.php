<?php

namespace Database\Seeders;

use App\Models\Deadline;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DeadlineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($countyId = null)
    {
        $items = [
            [
                'uuid' => Str::uuid(),
                'name' => 'Alerta',
                'days' => 5,
                'county_id' => $countyId ?? null
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Análise técnica',
                'days' => 15,
                'county_id' => $countyId ?? null
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Encaminhamentos',
                'days' => 15,
                'county_id' => $countyId ?? null
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Sala de vacina',
                'days' => 5,
                'county_id' => $countyId ?? null
            ],            
        ];
        foreach ($items as $value) {
            Deadline::updateOrCreate(
                [
                    'name' => $value['name'],
                    'county_id' => $value['county_id']
                ],
                [
                    'uuid' => $value['uuid'],
                    'days' => $value['days']
                ]
            );
        }
    }
}
