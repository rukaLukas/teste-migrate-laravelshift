<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuOccupation;
use App\Models\Occupation;
use App\Models\Pronoun;
use App\Models\TargetPublic;
use App\Models\Vaccine;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MenusOccupationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = Occupation::PERMISSIONS;
        foreach ($permissions as $key => $menus) {
            foreach ($menus as $menu) {
                $params = [
                    'occupation_id' => $key,
                    'menu_id' => Menu::where('name', $menu)->first()->id
                ];
                MenuOccupation::create($params);
            }
        }
    }
}
