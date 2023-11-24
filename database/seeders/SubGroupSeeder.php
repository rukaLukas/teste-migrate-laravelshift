<?php

namespace Database\Seeders;

use App\Models\SubGroup;
use Illuminate\Database\Seeder;

class SubGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SubGroup::factory()->count(10)->create();
    }
}
