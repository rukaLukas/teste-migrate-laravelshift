<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\TargetPublic;
use Illuminate\Database\Seeder;
use App\Models\GovernmentOffice;
use App\Models\ReasonDelayVaccine;
use App\Models\TypeReasonDelayVaccine;

class ReasonDelayVaccineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {        
        $reasons = [
            TypeReasonDelayVaccine::where('description', 'Atraso vacinal')->first()->id => [
                "Baixa percepção do risco de doenças imunopreveníveis",
                "Cumprindo medidas socioeducativas",
                "Problemas relacionados ao acesso ao serviço de saúde",
                "Horário dos serviços de saúde não compatíveis para pais ou responsáveis trabalhadores",
                "Agendamento para vacinação na unidade de saúde",
                "Medo de eventos adversos (reações)",
                "Preocupação com a segurança das vacinas",
                "Problemas relacionados a qualidade do serviço de saúde",
                "Estrutura insuficiente nas salas de vacina",
                "Profissionais de saúde não recomendaram",
                "Influência de Fake News/mídia",
                "Falta de interesse dos pais ou não acham importante  vacinar as crianças e/ou adolescente",
                "Extrema pobreza",
                "Falta de dinheiro para o transporte",
                "Criança ou adolescente com deficiência",
                "Criança ou adolescente em abrigo",
                "Criança ou adolescente em situação de rua",
                "Criança em vítima de abuso/violência familiar ou sexual",
                "Horário de funcionamento da Unidade não compatível",
                "Situação de emergência: enchente, inundação, seca (ou cheia e vazante - tem impacto nos ribeirinhos)",
                "Falta da vacina na Unidade"
            ],
            TypeReasonDelayVaccine::where('description', 'Não Vacinada')->first()->id => [
                "Baixa percepção do risco de doenças imunopreveníveis",
                "Medo de eventos adversos (reações) ",
                "Preocupação com a segurança das vacinas ",
                "Influência de Fake News/mídia ",
                "Questões culturais ou religiosas ",
                "Preferência por métodos naturais e medicinas complementares",
            ],
            TypeReasonDelayVaccine::where('description', 'Não ter resolvido o atraso vacinal na sala de vacina')->first()->id => [
                "Falta de vacina",
                "Falta de profissional habilitado para vacinação",
                "outro (especifique)"
            ],
            // TypeReasonDelayVaccine::where('description', 'Não ter a carteirinha')->first()->id => [
            //     "Não tenho",
            //     "Não sei onde está",
            //     "Perdi",
            // ]
        ];

        $contador = 0;
        $isForwarding = [13,14,18,19];       

        foreach ($reasons as $key => $value) {
            foreach($value as $itemReason) {
                $contador++;
                ReasonDelayVaccine::create([
                    'type_reason_delay_vaccine_id' => $key,
                    'description' => $itemReason,
                    'is_forwarding' => in_array($contador, $isForwarding) ? true : false
                ]);
            }
        }     
    }
}
