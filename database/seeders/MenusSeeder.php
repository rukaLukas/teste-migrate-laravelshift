<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Pronoun;
use App\Models\TargetPublic;
use App\Models\Vaccine;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MenusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $values = [
            'home',
            'dashboard',
            'accession',
            'records',
            'alerts',
            'events',
            'reports',
            'tools',
            'users',
            'configurations',
            'profile'
        ];
        foreach ($values as $value) {
            Menu::create(['name' => $value]);
        }
    }
}
