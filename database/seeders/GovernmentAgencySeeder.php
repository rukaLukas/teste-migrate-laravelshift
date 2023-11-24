<?php

namespace Database\Seeders;

use App\Models\GovernmentAgency;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class GovernmentAgencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        GovernmentAgency::factory()->count(5)->create();

    }
}
