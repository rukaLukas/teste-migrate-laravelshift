<?php

namespace Database\Seeders;

use App\Models\GovernmentOffice;
use App\Models\GovernmentOfficeUser;
use Illuminate\Database\Seeder;

class GovernmentOfficeUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        GovernmentOfficeUser::factory()->count(15)->create();
    }
}
