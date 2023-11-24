<?php

namespace Database\Seeders;

use App\Models\Breed;
use App\Models\GovernmentAgency;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BreedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $breeds = [
            'Branca', 
            'Preta',
            'Parda',
            'Amarela',
            'Indígena'
        ];

        foreach ($breeds as $value) {
            Breed::create([
                'name' => $value
            ]);
        }
    }
}
