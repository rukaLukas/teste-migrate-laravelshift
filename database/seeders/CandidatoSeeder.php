<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CandidatoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $query = "TRUNCATE TABLE candidatos;";

        $query .= "SELECT count(*)
                    INTO @exist
                    FROM information_schema.columns
                    WHERE table_schema = 'bav'
                    and COLUMN_NAME = 'id'
                    AND table_name = 'candidatos' LIMIT 1;                    
                    
                    set @query = IF(@exist > 0, 'ALTER TABLE `candidatos` DROP COLUMN `id`', 'select \'Existe\'');
                    
                    prepare stmt from @query;
                    
                    EXECUTE stmt;";
        
        $query .= "LOAD DATA INFILE '/handled_files/tse_files/consulta_cand_2020_BRASIL_otimizado.csv' 
                    INTO TABLE candidatos 
                    FIELDS TERMINATED BY ';' 
                    ENCLOSED BY '\"'
                    LINES TERMINATED BY '\n'
                    IGNORE 1 ROWS;";        
                    
        $query .= "SELECT count(*)
                    INTO @exist
                    FROM information_schema.columns
                    WHERE table_schema = 'bav'
                    and COLUMN_NAME = 'id'
                    AND table_name = 'candidatos' LIMIT 1;                    
                    
                    set @query = IF(@exist <= 0, 'ALTER TABLE candidatos ADD column `id` int(10) unsigned primary KEY AUTO_INCREMENT FIRST', 'select \'Existe\'');
                    
                    prepare stmt from @query;
                    
                    EXECUTE stmt;";
        
        DB::unprepared($query);
    }
}
