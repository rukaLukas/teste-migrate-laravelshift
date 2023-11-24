<?php

namespace Database\Seeders;

use App\Models\VaccineRoom;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VaccineRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vaccineRooms = [
            [
                'uuid' => Str::uuid(),
                'name' => 'UBS Sao Cristovao',
                'postalcode' => '41500-260',
                'street' => 'Rua Alto da Boa Vista',
                'state' => 'BA',
                'city' => 'Salvador',
                'district' => 'São Cristóvão',
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'UBS Dr Orlando Imbassahy',
                'postalcode' => '41205-510',
                'street' => 'R. Tancredo Neves',
                'state' => 'BA',
                'city' => 'Salvador',
                'district' => 'Bairro da Paz',
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'UBS NELSON COSTA',
                'postalcode' => '45656-150',
                'street' => 'RUA LIRIO',
                'state' => 'BA',
                'city' => 'Ilhéus',
                'district' => 'Nelson Costa',
            ],
        ];
        foreach ($vaccineRooms as $value) {
            VaccineRoom::create($value);
        }
    }
}
