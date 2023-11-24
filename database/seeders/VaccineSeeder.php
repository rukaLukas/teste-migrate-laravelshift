<?php

namespace Database\Seeders;

use App\Models\TargetPublic;
use App\Models\Vaccine;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VaccineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vaccinesChild = [
            [
                'uuid' => Str::uuid(),
                'name' => 'BCG',
                'schema' => 'Dose única',
                'dose' => '1',
                'aplication_age_month' => 0,
                'limit_age_year' => 5,
                'days_interval' => 0,
                'rules' => 'Até menor de 5 anos',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Criança')->first()->id,
                'genre' => 'M/F'
            ],   
            [
                'uuid' => Str::uuid(),
                'name' => 'Hepatite B',
                'schema' => 'Dose única',
                'dose' => '1',
                'aplication_age_month' => 0,
                'limit_age_year' => 0,
                'days_interval' => 0,
                'rules' => 'até 30 dias de idade',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Criança')->first()->id,
                'genre' => 'M/F'
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Poliomielite (VIP)',
                'schema' => '3 doses',
                'dose' => '1',
                'aplication_age_month' => 2,
                'limit_age_year' => 5,
                'days_interval' => 0,
                'rules' => 'Até menor de 5 anos',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Criança')->first()->id,
                'genre' => 'M/F'
            ], 
            [
                'uuid' => Str::uuid(),
                'name' => 'Poliomielite (VIP)',
                'schema' => '3 doses',
                'dose' => '2',
                'aplication_age_month' => 4,
                'limit_age_year' => 5,
                'days_interval' => 60,
                'rules' => 'Até menor de 5 anos',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Criança')->first()->id,
                'genre' => 'M/F'
            ],   
            [
                'uuid' => Str::uuid(),
                'name' => 'Poliomielite (VIP)',
                'schema' => '3 doses',
                'dose' => '3',
                'aplication_age_month' => 6,
                'limit_age_year' => 5,
                'days_interval' => 60,
                'rules' => 'Até menor de 5 anos',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Criança')->first()->id,
                'genre' => 'M/F'
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Rotavírus',
                'schema' => '2 doses',
                'dose' => '1',
                'aplication_age_month' => 2,
                'limit_age_year' => 0,
                'days_interval' => 30,
                'rules' => 'Idade mínima: 45 dias ;Idade máxima: 3 meses e 15 dias',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Criança')->first()->id,
                'genre' => 'M/F'
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Rotavírus',
                'schema' => '2 doses',
                'dose' => '2',
                'aplication_age_month' => 4,
                'limit_age_year' => 0,
                'days_interval' => 60,
                'rules' => 'Idade máxima: 7 meses e 29 dias;Até 7 meses e 29 dias',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Criança')->first()->id,
                'genre' => 'M/F'
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Pentavalente (DTP + Hib + HB)',
                'schema' => '3 doses',
                'dose' => '1',
                'aplication_age_month' => 2,
                'limit_age_year' => 6,
                'days_interval' => 0,
                'rules' => 'Até menor de 7 anos',  
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Criança')->first()->id,
                'genre' => 'M/F'
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Pentavalente (DTP + Hib + HB)',
                'schema' => '3 doses',
                'dose' => '2',
                'aplication_age_month' => 4,
                'limit_age_year' => 6,
                'days_interval' => 60,
                'rules' => 'Até menor de 7 anos',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Criança')->first()->id,
                'genre' => 'M/F'
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Pentavalente (DTP + Hib + HB)',
                'schema' => '3 doses',
                'dose' => '3',
                'aplication_age_month' => 6,
                'limit_age_year' => 6,
                'days_interval' => 60,
                'rules' => 'Até menor de 7 anos',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Criança')->first()->id,
                'genre' => 'M/F'
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Pneumocócica 10 Valente',
                'schema' => '2 doses',
                'dose' => '1',
                'aplication_age_month' => 2,
                'limit_age_year' => 4,
                'days_interval' => 0,
                'rules' => 'Até menor de 4 anos',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Criança')->first()->id,
                'genre' => 'M/F'
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Pneumocócica 10 Valente',
                'schema' => '2 doses',
                'dose' => '2',
                'aplication_age_month' => 4,
                'limit_age_year' => 4,
                'days_interval' => 60,
                'rules' => 'Até menor de 4 anos',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Criança')->first()->id,
                'genre' => 'M/F'
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Meningocócica C',
                'schema' => '2 doses',
                'dose' => '1',
                'aplication_age_month' => 3,
                'limit_age_year' => 4,
                'days_interval' => 0,
                'rules' => 'Até menor de 4 anos',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Criança')->first()->id,
                'genre' => 'M/F'
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Meningocócica C',
                'schema' => '2 doses',
                'dose' => '2',
                'aplication_age_month' => 5,
                'limit_age_year' => 4,
                'days_interval' => 60,
                'rules' => 'Até menor de 4 anos',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Criança')->first()->id,
                'genre' => 'M/F'
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Influenza',
                'schema' => 'Anual',
                'dose' => '1',
                'aplication_age_month' => 6,
                'limit_age_year' => 5,
                'days_interval' => 0,
                'rules' => 'Até menor de 6 anos - 6 meses a menores de 4 anos, se maior que 5 anos continuar aplicando anualdoenças crônicas ou condições clínicas especiais.',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Criança')->first()->id,
                'genre' => 'M/F'
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Febre Amarela',
                'schema' => 'Dose inicial',
                'dose' => '1',
                'aplication_age_month' => 9,
                'limit_age_year' => 59,
                'days_interval' => 0,
                'rules' => 'Até menor de 59 anos',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Criança')->first()->id,
                'genre' => 'M/F'           
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Pneumocócica 10 valente',
                'schema' => 'Reforço',
                'dose' => '1',
                'aplication_age_month' => 12,
                'limit_age_year' => 4,
                'days_interval' => 0,
                'rules' => 'Até menor de 4 anos',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Criança')->first()->id,
                'genre' => 'M/F'               
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Meningocócica C',
                'schema' => 'Reforço',
                'dose' => '1',
                'aplication_age_month' => 12,
                'limit_age_year' => 4,
                'days_interval' => 0,
                'rules' => 'Até menor de 4 anos',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Criança')->first()->id,
                'genre' => 'M/F'
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Sarampo, Caxumba e Rubéola (SCR)',
                'schema' => 'Dose única',
                'dose' => '1',
                'aplication_age_month' => 12,
                'limit_age_year' => 59,
                'days_interval' => 0,
                'rules' => 'Até menor de 59 anos',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Criança')->first()->id,
                'genre' => 'M/F'
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'DTP',
                'schema' => '1º Reforço',
                'dose' => '1',
                'aplication_age_month' => 15,
                'limit_age_year' => 6,
                'days_interval' => 0,
                'rules' => 'Até menor de 7 anos',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Criança')->first()->id,
                'genre' => 'M/F'
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'VOP (Vacina Oral Poliomielite)',
                'schema' => '1º Reforço',
                'dose' => '1',
                'aplication_age_month' => 15,
                'limit_age_year' => 4,
                'days_interval' => 0,
                'rules' => 'Até menor de 5 anos',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Criança')->first()->id,
                'genre' => 'M/F'
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Sarampo, Caxumba,Rubéola e Varicela (tetraviral)',
                'schema' => 'Dose única',
                'dose' => '1',
                'aplication_age_month' => 15,
                'limit_age_year' => 59,
                'days_interval' => 0,
                'rules' => 'Até menor de 60 anos',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Criança')->first()->id,
                'genre' => 'M/F'          
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Hepatite A',
                'schema' => 'Dose única',
                'dose' => '1',
                'aplication_age_month' => 15,
                'limit_age_year' => 4,
                'days_interval' => 0,
                'rules' => 'Até menor de 5 anos',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Criança')->first()->id,
                'genre' => 'M/F'
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'DTP',
                'schema' => '2º Reforço',
                'dose' => '1',
                'aplication_age_month' => 48,
                'limit_age_year' => 6,
                'days_interval' => 0,
                'rules' => 'Até menor de 7 anos',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Criança')->first()->id,
                'genre' => 'M/F'         
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'VOP (Vacina Oral Poliomielite)',
                'schema' => '2º Reforço',
                'dose' => '1',
                'aplication_age_month' => 48,
                'limit_age_year' => 4,
                'days_interval' => 0,
                'rules' => 'Até menor de 5 anos',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Criança')->first()->id,
                'genre' => 'M/F'               
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Febre Amarela',
                'schema' => 'Reforço',
                'dose' => '1',
                'aplication_age_month' => 48,
                'limit_age_year' => 59,
                'days_interval' => 0,
                'rules' => 'Até menor de 59 anos',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Criança')->first()->id,
                'genre' => 'M/F'               
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Varicela',
                'schema' => 'Dose única',
                'dose' => '1',
                'aplication_age_month' => 48,
                'limit_age_year' => 6,
                'days_interval' => 0,
                'rules' => 'Até menor de 7 anos',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Criança')->first()->id,
                'genre' => 'M/F'             
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'HPV meninas',
                'schema' => '2 doses',
                'dose' => '1',
                'aplication_age_month' => 108,
                'limit_age_year' => 14,
                'days_interval' => 0,
                'rules' => 'Até menor de 15 anos',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Criança')->first()->id,
                'genre' => 'F'             
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'HPV meninas',
                'schema' => '2 doses',
                'dose' => '2',
                'aplication_age_month' => 108,
                'limit_age_year' => 14,
                'days_interval' => 180,
                'rules' => 'Até menor de 15 anos',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Criança')->first()->id,
                'genre' => 'F'            
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'HPV meninos',
                'schema' => '2 doses',
                'dose' => '1',
                'aplication_age_month' => 108,
                'limit_age_year' => 14,
                'days_interval' => 0,
                'rules' => 'Até menor de 15 anos',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Criança')->first()->id,
                'genre' => 'M'               
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'HPV meninos',
                'schema' => '2 doses',
                'dose' => '2',
                'aplication_age_month' => 108,
                'limit_age_year' => 14,
                'days_interval' => 180,
                'rules' => 'Até menor de 15 anos',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Criança')->first()->id,
                'genre' => 'M'               
            ]
        ];

        $vaccinesTeenager = [
            [
                'uuid' => Str::uuid(),
                'name' => 'Hepatite B',
                'schema' => '3 doses',
                'dose' => '1',
                'aplication_age_month' => 0,
                'limit_age_year' => 0,
                'days_interval' => 0,
                'rules' => 'analisar situação vacinal/vacinação anterior; Até o fim da vida',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Adolescente')->first()->id,
                'genre' => 'M/F'
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Hepatite B',
                'schema' => '3 doses',
                'dose' => '2',
                'aplication_age_month' => 0,
                'limit_age_year' => 0,
                'days_interval' => 60,
                'rules' => 'analisar situação vacinal/vacinação anterior; Até o fim da vida',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Adolescente')->first()->id,
                'genre' => 'M/F'
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Hepatite B',
                'schema' => '3 doses',
                'dose' => '3',
                'aplication_age_month' => 0,
                'limit_age_year' => 0,
                'days_interval' => 60,
                'rules' => 'analisar situação vacinal/vacinação anterior; Até o fim da vida',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Adolescente')->first()->id,
                'genre' => 'M/F'
            ],   
            [
                'uuid' => Str::uuid(),
                'name' => 'Dupla Adulto (dT)',
                'schema' => '3 doses',
                'dose' => '1',
                'aplication_age_month' => 84,
                'limit_age_year' => 0,
                'days_interval' => 0,
                'rules' => 'analisar situação vacinal/vacinação anterior; Até o fim da vida',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Adolescente')->first()->id,
                'genre' => 'M/F'
            ],   
            [
                'uuid' => Str::uuid(),
                'name' => 'Dupla Adulto (dT)',
                'schema' => '3 doses',
                'dose' => '2',
                'aplication_age_month' => 84,
                'limit_age_year' => 0,
                'days_interval' => 60,
                'rules' => 'analisar situação vacinal/vacinação anterior; Até o fim da vida',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Adolescente')->first()->id,
                'genre' => 'M/F'
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Dupla Adulto (dT)',
                'schema' => '3 doses',
                'dose' => '3',
                'aplication_age_month' => 84,
                'limit_age_year' => 0,
                'days_interval' => 60,
                'rules' => 'analisar situação vacinal/vacinação anterior; Até o fim da vida',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Adolescente')->first()->id,
                'genre' => 'M/F'
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Febre Amarela',
                'schema' => 'Dose única',
                'dose' => '1',
                'aplication_age_month' => 9,
                'limit_age_year' => 59,
                'days_interval' => 0,
                'rules' => 'analisar situação vacinal/vacinação anterior; Até menor de 60 anos',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Adolescente')->first()->id,
                'genre' => 'M/F'
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Sarampo, Caxumba e Rubéola (SCR)',
                'schema' => '2 doses',
                'dose' => '1',
                'aplication_age_month' => 12,
                'limit_age_year' => 59,
                'days_interval' => 0,
                'rules' => 'analisar situação vacinal/vacinação anterior; Até menor de 60 anos',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Adolescente')->first()->id,
                'genre' => 'M/F'
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Sarampo, Caxumba e Rubéola (SCR)',
                'schema' => '2 doses',
                'dose' => '2',
                'aplication_age_month' => 0,
                'limit_age_year' => 59,
                'days_interval' => 60,
                'rules' => 'analisar situação vacinal/vacinação anterior; Até menor de 60 anos',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Adolescente')->first()->id,
                'genre' => 'M/F'
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Meningocócica C',
                'schema' => 'Dose única',
                'dose' => '1',
                'aplication_age_month' => 132,
                'limit_age_year' => 12,
                'days_interval' => 0,
                'rules' => 'analisar situação vacinal/vacinação anterior; Até menor de 13 anos',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Adolescente')->first()->id,
                'genre' => 'M/F'
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'HPV meninas',
                'schema' => '2 doses',
                'dose' => '1',
                'aplication_age_month' => 108,
                'limit_age_year' => 14,
                'days_interval' => 0,
                'rules' => 'analisar situação vacinal/vacinação anterior; Até menor de 15 anos',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Adolescente')->first()->id,
                'genre' => 'F'
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'HPV meninas',
                'schema' => '2 doses',
                'dose' => '2',
                'aplication_age_month' => 108,
                'limit_age_year' => 14,
                'days_interval' => 180,
                'rules' => 'analisar situação vacinal/vacinação anterior; Até menor de 15 anos',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Adolescente')->first()->id,
                'genre' => 'F'
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'HPV meninos',
                'schema' => '2 doses',
                'dose' => '1',
                'aplication_age_month' => 132,
                'limit_age_year' => 14,
                'days_interval' => 0,
                'rules' => 'analisar situação vacinal/vacinação anterior; Até menor de 15 anos',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Adolescente')->first()->id,
                'genre' => 'M'
            ],   
            [
                'uuid' => Str::uuid(),
                'name' => 'HPV meninos',
                'schema' => '2 doses',
                'dose' => '2',
                'aplication_age_month' => 132,
                'limit_age_year' => 14,
                'days_interval' => 180,
                'rules' => 'analisar situação vacinal/vacinação anterior; Até menor de 15 anos',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Adolescente')->first()->id,
                'genre' => 'M'
            ]
        ];

        // Adultos
        /*
        $vaccinesAdult = [
            [
                'uuid' => Str::uuid(),
                'name' => 'Dupla Adulto (dT)',
                'schema' => '3 doses',
                'dose' => '1',
                'aplication_age_month' => 84,
                'limit_age_year' => 0,
                'days_interval' => 0,
                'rules' => 'analisar situação vacinal/vacinação anterior; Até o fim da vida',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Adulto')->first()->id
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Dupla Adulto (dT)',
                'schema' => '3 doses',
                'dose' => '2',
                'aplication_age_month' => 84,
                'limit_age_year' => 0,
                'days_interval' => 60,
                'rules' => 'analisar situação vacinal/vacinação anterior; Até o fim da vida',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Adulto')->first()->id
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Dupla Adulto (dT)',
                'schema' => '3 doses',
                'dose' => '3',
                'aplication_age_month' => 84,
                'limit_age_year' => 0,
                'days_interval' => 60,
                'rules' => 'analisar situação vacinal/vacinação anterior; Até o fim da vida',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Adulto')->first()->id
            ],   
            [
                'uuid' => Str::uuid(),
                'name' => 'Dupla Adulto (dT)',
                'schema' => 'reforço a cada 10 anos',
                'dose' => '',
                'aplication_age_month' => 84,
                'limit_age_year' => 0,
                'days_interval' => 3650,                
                'rules' => 'analisar situação vacinal/vacinação anterior; Até o fim da vida',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Adulto')->first()->id
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Hepatite B',
                'schema' => '3 doses',
                'dose' => '1',
                'aplication_age_month' => 0,
                'limit_age_year' => 0,
                'days_interval' => 0,
                'rules' => 'analisar situação vacinal/vacinação anterior; Até o fim da vida',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Adulto')->first()->id
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Hepatite B',
                'schema' => '3 doses',
                'dose' => '2',
                'aplication_age_month' => 0,
                'limit_age_year' => 0,
                'days_interval' => 60,
                'rules' => 'analisar situação vacinal/vacinação anterior; Até o fim da vida',
                'updated_at' => Carbon::now(),    
                'target_public_id' => TargetPublic::where('name', 'Adulto')->first()->id            
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Hepatite B',
                'schema' => '3 doses',
                'dose' => '3',
                'aplication_age_month' => 0,
                'limit_age_year' => 0,
                'days_interval' => 60,
                'rules' => 'analisar situação vacinal/vacinação anterior; Até o fim da vida',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Adulto')->first()->id
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Sarampo, Caxumba e Rubéola (SCR)',
                'schema' => '2 doses',
                'dose' => '1',
                'aplication_age_month' => 12,
                'limit_age_year' => 29,
                'days_interval' => 0,
                'rules' => 'analisar situação vacinal/vacinação anterior; Até menor de 30 anos',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Adulto')->first()->id
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Sarampo, Caxumba e Rubéola (SCR)',
                'schema' => '2 doses',
                'dose' => '2',
                'aplication_age_month' => 12,
                'limit_age_year' => 29,
                'days_interval' => 60,
                'rules' => 'analisar situação vacinal/vacinação anterior; Até menor de 30 anos',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Adulto')->first()->id
            ],
            // TODO verificar se essa data de aplicaco da ultima dose de SCR está correto
            [
                'uuid' => Str::uuid(),
                'name' => 'Sarampo, Caxumba e Rubéola (SCR)',
                'schema' => 'Dose única',
                'dose' => '1',
                'aplication_age_month' => 360,
                'limit_age_year' => 59,
                'days_interval' => 0,
                'rules' => 'analisar situação vacinal/vacinação anterior; Até menor de 30 anos',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Adulto')->first()->id
            ]                             
        ];
        */

        // Idosos
        /*
        $vaccinesElderly = [
            [
                'uuid' => Str::uuid(),
                'name' => 'Dupla Adulto (dT)',
                'schema' => '3 doses',
                'dose' => '1',
                'aplication_age_month' => 84,
                'limit_age_year' => 0,
                'days_interval' => 0,
                'rules' => 'analisar situação vacinal/vacinação anterior; Até o fim da vida',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Idoso')->first()->id
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Dupla Adulto (dT)',
                'schema' => '3 doses',
                'dose' => '2',
                'aplication_age_month' => 84,
                'limit_age_year' => 0,
                'days_interval' => 60,
                'rules' => 'analisar situação vacinal/vacinação anterior; Até o fim da vida',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Idoso')->first()->id
            ],   
            [
                'uuid' => Str::uuid(),
                'name' => 'Dupla Adulto (dT)',
                'schema' => '3 doses',
                'dose' => '3',
                'aplication_age_month' => 84,
                'limit_age_year' => 0,
                'days_interval' => 60,
                'rules' => 'analisar situação vacinal/vacinação anterior; Até o fim da vida',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Idoso')->first()->id
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Hepatite B',
                'schema' => '3 doses',
                'dose' => '1',
                'aplication_age_month' => 0,
                'limit_age_year' => 0,
                'days_interval' => 0,
                'rules' => 'analisar situação vacinal/vacinação anterior; Até o fim da vida',
                'updated_at' => Carbon::now(), 
                'target_public_id' => TargetPublic::where('name', 'Idoso')->first()->id
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Hepatite B',
                'schema' => '3 doses',
                'dose' => '2',
                'aplication_age_month' => 84,
                'limit_age_year' => 0,
                'days_interval' => 0,
                'rules' => 'analisar situação vacinal/vacinação anterior; Até o fim da vida',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Idoso')->first()->id
            ],   
            [
                'uuid' => Str::uuid(),
                'name' => 'Hepatite B',
                'schema' => '3 doses',
                'dose' => '3',
                'aplication_age_month' => 84,
                'limit_age_year' => 0,
                'days_interval' => 0,
                'rules' => 'analisar situação vacinal/vacinação anterior; Até o fim da vida',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Idoso')->first()->id
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Influenza',
                'schema' => 'anual',
                'dose' => '1',
                'aplication_age_month' => 0,
                'limit_age_year' => 0,
                'days_interval' => 360,
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Idoso')->first()->id
            ]                                              
        ];
        */
    
        $vaccinesPregnant = [
            [
                'uuid' => Str::uuid(),
                'name' => 'Hepatite B',
                'schema' => '3 doses',
                'dose' => '1',
                'aplication_age_month' => 0,
                'limit_age_year' => 0,
                'days_interval' => 0,
                'rules' => 'analisar situação vacinal/vacinação anterior; Até o fim da vida',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Gestante')->first()->id,
                'genre' => 'M/F'
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Hepatite B',
                'schema' => '3 doses',
                'dose' => '2',
                'aplication_age_month' => 0,
                'limit_age_year' => 0,
                'days_interval' => 60,
                'rules' => 'analisar situação vacinal/vacinação anterior; Até o fim da vida',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Gestante')->first()->id,
                'genre' => 'M/F'
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Hepatite B',
                'schema' => '3 doses',
                'dose' => '3',
                'aplication_age_month' => 0,
                'limit_age_year' => 0,
                'days_interval' => 60,
                'rules' => 'analisar situação vacinal/vacinação anterior; Até o fim da vida',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Gestante')->first()->id,
                'genre' => 'M/F'
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Dupla Adulto (dT)',
                'schema' => '2 doses',
                'dose' => '1',
                'aplication_age_month' => 0,
                'limit_age_year' => 0,
                'days_interval' => 0,
                'rules' => 'analisar situação vacinal/vacinação anterior; Até o fim da vida',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Gestante')->first()->id,
                'genre' => 'M/F'
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Dupla Adulto (dT)',
                'schema' => '2 doses',
                'dose' => '2',
                'aplication_age_month' => 0,
                'limit_age_year' => 0,
                'days_interval' => 60,
                'rules' => 'analisar situação vacinal/vacinação anterior; Até o fim da vida',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Gestante')->first()->id,
                'genre' => 'M/F'
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'dTpa adulto - Difteria, Tétano e Coqueluche acelular',
                'schema' => 'Dose única',
                'dose' => '1',
                'aplication_age_month' => 0,
                'limit_age_year' => 0,
                'days_interval' => 0,
                'rules' => '20ª semana de gestação; 60 dias após a segunda dose da dT; mesmo que o esquema vacinal esteja completo; Até 45 dias após o parto',
                'updated_at' => Carbon::now(),
                'target_public_id' => TargetPublic::where('name', 'Gestante')->first()->id,
                'genre' => 'M/F'  
            ]                                  
        ];

      
        $vaccines = array_merge($vaccinesChild, $vaccinesTeenager, $vaccinesPregnant);

        foreach ($vaccines as $value) {            
            // Vaccine::updateOrCreate([                
            //     'name' => $value['name'],
            //     // 'dose' => $value['dose'], 
            //     'schema' => $value['schema'],               
            //     'target_public_id' => $value['target_public_id']              
            // ], $value);  

            Vaccine::create($value);
        }
    }
}