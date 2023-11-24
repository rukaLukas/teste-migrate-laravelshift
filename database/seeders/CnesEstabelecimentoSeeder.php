<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CnesEstabelecimentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $query = "LOAD DATA INFILE '/handled_files/cnes_files/tbEstabelecimento.csv'
                    INTO TABLE cnes_estabelecimento
                    FIELDS TERMINATED BY ';' 
                    ENCLOSED BY '\"' 
                    LINES TERMINATED BY '\n'
                    IGNORE 1 ROWS
                    (CO_UNIDADE,
                    CO_CNES,
                    @dummy,
                    @dummy,
                    @dummy,
                    @dummy,
                    NO_FANTASIA,
                    NO_LOGRADOURO,
                    NU_ENDERECO,
                    NO_COMPLEMENTO,
                    NO_BAIRRO,
                    @dummy,
                    @dummy,
                    @dummy,
                    @dummy,
                    @dummy,
                    NU_TELEFONE,
                    @dummy,
                    @dummy,
                    @dummy,
                    @dummy,
                    @dummy,
                    @dummy,
                    @dummy,
                    @dummy,
                    @dummy,
                    @dummy,
                    @dummy,
                    @dummy,
                    @dummy,
                    CO_ESTADO_GESTOR,
                    CO_MUNICIPIO_GESTOR,
                    @dummy,
                    @dummy,
                    @dummy,
                    @dummy,
                    @dummy,
                    @dummy,
                    @dummy,
                    NU_LATITUDE,
                    NU_LONGITUDE,
                    @dummy,
                    @dummy,
                    @dummy,
                    @dummy,
                    @dummy,
                    @dummy,
                    CO_TIPO_UNIDADE,
                    @dummy,
                    @dummy,
                    @dummy,
                    CO_TIPO_ESTABELECIMENTO,
                    @dummy,
                    @dummy);";

        $query .= "DELETE FROM cnes_estabelecimento WHERE CO_TIPO_ESTABELECIMENTO NOT IN (001, 1)";

        DB::unprepared($query);
    }
}
