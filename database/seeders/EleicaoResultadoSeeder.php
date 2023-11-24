<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EleicaoResultadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $query = "LOAD DATA INFILE '/tse_files/votacao_candidato_munzona_2020_BRASIL.csv' 
                    INTO TABLE eleicao_resultados 
                    FIELDS TERMINATED BY ';' 
                    ENCLOSED BY '\"'
                    LINES TERMINATED BY '\n'
                    IGNORE 1 ROWS;";

        $query .= "ALTER TABLE eleicao_resultados ADD column `id` int(10) unsigned primary KEY AUTO_INCREMENT FIRST;";
        DB::unprepared($query);
    }
}
