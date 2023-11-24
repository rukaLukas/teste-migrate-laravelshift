<?php

namespace Database\Seeders;

use App\Models\Forwarding;
use Illuminate\Database\Seeder;

class ForwardingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $forwarding = [
            [
                'description' => 'Secretaria Municipal de Educação',
                'email' => 'secretaria_de_educacao@prefeitura.gov.br'
            ],
            [
                'description' => 'Secretaria Municipal de Assistência Social',
                'email' => 'secretaria_de_assistencia_social@prefeitura.gov.br'
            ],
            [
                'description' => 'Secretaria do município',
                'email' => 'sec_do_municipio@prefeitura.gov.br'
            ],
        ];

        foreach ($forwarding as $key => $value) {
            Forwarding::create($value);
        }
    }
}
