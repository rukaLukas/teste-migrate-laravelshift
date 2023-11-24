<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            ['id' => 1, 'name' => 'Norte'],
            ['id' => 2, 'name' => 'Nordeste'],
            ['id' => 3, 'name' => 'Sudeste'],
            ['id' => 4, 'name' => 'Sul'],
            ['id' => 5, 'name' => 'Centro-Oeste'],
        ];

        foreach ($items as $item) {
            Region::create($item);
        }
    }
}
