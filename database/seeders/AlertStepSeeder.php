<?php

namespace Database\Seeders;

use App\Models\Occupation;
use App\Models\User;
use App\Models\Record;
use App\Models\AlertStep;
use App\Models\StatusAlert;
use App\Models\CommentRecord;
use Illuminate\Database\Seeder;

class AlertStepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ptBrFaker = \Faker\Factory::create('pt_BR');
        $user = User::where(['occupation_id' => Occupation::COORDENADOR_OPERACIONAL_SAUDE])->first()->id;
        $record = Record::all()->last()->id;
        for ($i = 1; $i < 6; $i++) {
            AlertStep::firstOrCreate(
                [
                    'record_id' => $record,
                    'status_alert_id' => $i,
                    'user_id' => $user,
                ],
                [
                    'record_id' => $record,
                    'status_alert_id' => $i,
                    'user_id' => $user,
                ]
            );
        }
    }

}
