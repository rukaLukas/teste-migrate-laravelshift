<?php

namespace Database\Seeders;

use App\Models\ReasonDelayVaccine;
use Illuminate\Database\Seeder;

class ReasonsVaccineDelaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reasons = [
            1 => [
                'Baixa percepção do risco de doenças imunopreveníveis',
                'Problemas relacionados ao acesso ao serviço de saúde',
                'Horário dos serviços de saúde',
                'Agendamento para vacinação',
                'Medo de eventos adversos (reações)',
                'Preocupação com a segurança das vacinas',
                'Problemas relacionados à qualidade do serviço de saúde',
                'Estrutura insuficiente nas salas de vacina',
                'Questões culturais ou religiosas',
                'Profissionais de saúde não recomendaram',
                'Influência de fake news/mídia',
                'Falta de preocupação dos pais em vacinar as crianças',
                'Extrema pobreza',
                'Falta de dinheiro para o transporte',
                'Criança com deficiência',
                'Criança em abrigo',
                'Criança em situação de rua',
                'Criança vítima de abuso/violência familiar ou sexual'
            ],
            2 => [
                'Baixa percepção do risco de doenças imunopreveníveis',
                'Problemas relacionados ao acesso ao serviço de saúde',
                'Horário dos serviços de saúde',
                'Agendamento para vacinação',
                'Medo de eventos adversos (reações)',
                'Preocupação com a segurança das vacinas',
                'Problemas relacionados à qualidade do serviço de saúde',
                'Estrutura insuficiente nas salas de vacina',
                'Questões culturais ou religiosas',
                'Profissionais de saúde não recomendaram',
                'Influência de fake news/mídia',
                'Falta de preocupação dos pais em vacinar os adolescentes',
                'Extrema pobreza',
                'Falta de dinheiro para o transporte',
                'Adolescente com deficiência',
                'Adolescente em abrigo',
                'Adolescente em situação de rua',
                'Adolescente vítima de abuso/violência familiar ou sexual'
            ],
            3 => [
                'Baixa percepção do risco de doenças imunopreveníveis',
                'Problemas relacionados ao acesso ao serviço de saúde',
                'Horário dos serviços de saúde',
                'Agendamento para vacinação',
                'Medo de eventos adversos (reações)',
                'Preocupação com a segurança das vacinas',
                'Problemas relacionados à qualidade do serviço de saúde',
                'Estrutura insuficiente nas salas de vacina',
                'Questões culturais ou religiosas',
                'Profissionais de saúde não recomendaram',
                'Influência de fake news/mídia',
                'Extrema pobreza',
                'Falta de dinheiro para o transporte',
                'Gestante com deficiência',
                'Gestante em abrigo',
                'Gestante em situação de rua',
                'Gestante vítima de abuso/violência familiar ou sexual'
            ]
        ];

        foreach ($reasons as $key => $value) {
            foreach($value as $itemReason) {
                ReasonDelayVaccine::create([
                    'target_public_id' => $key,
                    'description' => $itemReason,
                    'is_send_social_assistence' => false,
                    'to' => 'Atraso vacinal',
                    'forwarding' => rand(0, 1) === 1 ? 'Assistência Social' : ''
                ]);
            }
        }
    }
}
