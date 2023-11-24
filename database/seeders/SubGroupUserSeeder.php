<?php

namespace Database\Seeders;

use App\Models\SubGroupUser;
use Illuminate\Database\Seeder;

class SubGroupUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        SubGroupUser::factory()->count(5)->create();
    }
}
