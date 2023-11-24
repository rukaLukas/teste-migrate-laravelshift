<?php

namespace App\Listeners;

use App\Events\SubGroupCreatedEvent;
use App\Models\Group;
use App\Models\UnderSubGroup;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class SubGroupCreatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(SubGroupCreatedEvent $event)
    {
        $codigoIbge = substr($event->subGroup->group->county->codigo_ibge, 0, -1);
        $subGroup = $event->subGroup;
        $cnes = DB::table('cnes_estabelecimento')->where('CO_MUNICIPIO_GESTOR', $codigoIbge)->get();

        $data = [];
        foreach ($cnes as $cne) {
            $data[] = array(
                    'uuid' => Uuid::uuid4(),
                    'name' => $cne->NO_FANTASIA,
                    'logradouro' => $cne->NO_LOGRADOURO,
                    'endereco' => $cne->NU_ENDERECO,
                    'bairro' => $cne->NO_BAIRRO,
                    'latitude' => $cne->NU_LATITUDE,
                    'longitude' => $cne->NU_LONGITUDE,
                    'logradouro' => $cne->NO_LOGRADOURO,
                    'endereco' => $cne->NU_ENDERECO,
                    'bairro' => $cne->NO_BAIRRO,
                    'latitude' => $cne->NU_LATITUDE,
                    'longitude' => $cne->NU_LONGITUDE,
                    'sub_group_id' => $subGroup->id,
                    'created_at' => now(),
                    'updated_at' => now()
                );
        }
        UnderSubGroup::insert($data);
    }
}
