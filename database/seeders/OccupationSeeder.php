<?php

namespace Database\Seeders;

use App\Models\Occupation;
use App\Models\Pronoun;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OccupationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $occupations = Occupation::OCCUPATIONS;

        foreach ($occupations as $key => $value) {
            Occupation::firstOrCreate([
                'name' => $value,
            ],
            [
                'id' => $key,
                'uuid' => Str::uuid(),
                'name' => $value
            ]);
        }
    }
}
