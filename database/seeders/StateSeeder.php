<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            ['id' => 1, 'name' => 'Acre', 'sigla' => 'AC' , 'region_id' => 1, 'codigo_uf' => 12],
            ['id' => 2, 'name' => 'Alagoas', 'sigla' => 'AL' , 'region_id' => 2, 'codigo_uf' => 27],
            ['id' => 3, 'name' => 'Amapá', 'sigla' => 'AP' , 'region_id' => 1, 'codigo_uf' => 16],
            ['id' => 4, 'name' => 'Amazonas', 'sigla' => 'AM' , 'region_id' => 1, 'codigo_uf' => 13],
            ['id' => 5, 'name' => 'Bahia', 'sigla' => 'BA' , 'region_id' => 2, 'codigo_uf' => 29],
            ['id' => 6, 'name' => 'Ceará', 'sigla' => 'CE' , 'region_id' => 2, 'codigo_uf' => 23],
            ['id' => 7, 'name' => 'Distrito Federal', 'sigla' => 'DF' , 'region_id' => 5, 'codigo_uf' => 53],
            ['id' => 8, 'name' => 'Espírito Santo' , 'sigla' => 'ES' ,'region_id' => 3, 'codigo_uf' => 32],
            ['id' => 9, 'name' => 'Goiás', 'sigla' => 'GO' , 'region_id' => 5, 'codigo_uf' => 52],
            ['id' => 10, 'name' => 'Maranhão', 'sigla' => 'MA', 'region_id' => 2, 'codigo_uf' => 21],
            ['id' => 11, 'name' => 'Mato Grosso', 'sigla' => 'MT', 'region_id' => 5, 'codigo_uf' => 51],
            ['id' => 12, 'name' => 'Mato Grosso do Sul', 'sigla' => 'MS' , 'region_id' => 5, 'codigo_uf' => 50],
            ['id' => 13, 'name' => 'Minas Gerais', 'sigla' => 'MG', 'region_id' => 3, 'codigo_uf' => 31],
            ['id' => 14, 'name' => 'Pará', 'sigla' => 'PA', 'region_id' => 1, 'codigo_uf' => 15],
            ['id' => 15, 'name' => 'Paraíba', 'sigla' => 'PB', 'region_id' => 2, 'codigo_uf' => 25],
            ['id' => 16, 'name' => 'Paraná', 'sigla' => 'PR',  'region_id' => 4, 'codigo_uf' => 41],
            ['id' => 17, 'name' => 'Pernambuco', 'sigla' => 'PE', 'region_id' => 2, 'codigo_uf' => 26],
            ['id' => 18, 'name' => 'Piauí', 'sigla' => 'PI', 'region_id' => 2, 'codigo_uf' => 22],
            ['id' => 19, 'name' => 'Rio de Janeiro', 'sigla' => 'RJ', 'region_id' => 3, 'codigo_uf' => 33],
            ['id' => 20, 'name' => 'Rio Grande do Norte' , 'sigla' => 'RN' , 'region_id' => 2, 'codigo_uf' => 24],
            ['id' => 21, 'name' => 'Rio Grande do Sul' , 'sigla' => 'RS' , 'region_id' => 4, 'codigo_uf' => 43],
            ['id' => 22, 'name' => 'Rondônia', 'sigla' => 'RO', 'region_id' => 1, 'codigo_uf' => 11],
            ['id' => 23, 'name' => 'Roraima', 'sigla' => 'RR', 'region_id' => 1, 'codigo_uf' => 14],
            ['id' => 24, 'name' => 'Santa Catarina', 'sigla' => 'SC', 'region_id' => 4, 'codigo_uf' => 42],
            ['id' => 25, 'name' => 'São Paulo', 'sigla' => 'SP', 'region_id' => 3, 'codigo_uf' => 35],
            ['id' => 26, 'name' => 'Sergipe', 'sigla' => 'SE' ,'region_id' => 2, 'codigo_uf' => 28],
            ['id' => 27, 'name' => 'Tocantins', 'sigla' => 'TO', 'region_id' => 1, 'codigo_uf' => 17],
        ];

        foreach ($items as $item) {
            State::create($item);
        }
    }
}
