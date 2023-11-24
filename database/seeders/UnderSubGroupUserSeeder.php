<?php

namespace Database\Seeders;

use App\Models\UnderSubGroupUser;
use Illuminate\Database\Seeder;

class UnderSubGroupUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UnderSubGroupUser::factory()->count(8)->create();
    }
}
