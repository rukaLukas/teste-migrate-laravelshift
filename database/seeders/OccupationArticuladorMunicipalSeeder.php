<?php

namespace Database\Seeders;

use App\Models\Occupation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OccupationArticuladorMunicipalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $occupation = Occupation::find(Occupation::ARTICULADOR_MUNICIPAL);
        if (!$occupation) {
            $params = [
                'id' => 10,
                'name' => Occupation::OCCUPATIONS[Occupation::ARTICULADOR_MUNICIPAL],
                'uuid' => Str::uuid()
            ];
            Occupation::create($params);
        }
    }
}
