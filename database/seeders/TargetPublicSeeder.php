<?php

namespace Database\Seeders;

use App\Models\TargetPublic;
use Illuminate\Database\Seeder;

class TargetPublicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = array('Criança', 'Adolescente', 'Gestante');
        foreach ($types as $key => $value) {
            TargetPublic::create([
                'name' => $value,
            ]);
        }
    }
}
