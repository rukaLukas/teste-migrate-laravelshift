<?php

namespace Database\Seeders;

use App\Models\County;
use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $grupos = [
            [
                'name' => 'Região',
                'county_id' => User::inRandomOrder()->first()->county_id,
            ],
            [
                'name' => 'Região 2',
                'county_id' => User::inRandomOrder()->first()->county_id,
            ],
        ];

        foreach ($grupos as $key => $value) {
            Group::create($value);
        }

        Group::factory()->count(8)->create();
    }
}
