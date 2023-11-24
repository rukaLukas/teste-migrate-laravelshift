<?php

namespace Database\Seeders;

use App\Models\Pronoun;
use App\Models\TargetPublic;
use App\Models\Vaccine;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PronounSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $values = [
            [
                'uuid' => Str::uuid(),
                'name' => 'Sr.',
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Srª.',
            ]
        ];
        foreach ($values as $value) {
            Pronoun::create($value);
        }
    }
}
