<?php

namespace App\Infra\Repository;

use App\Infra\Repository\Configuration\TargetPublicRepository;
use App\Models\Alert;
use App\Models\Record;
use App\Models\AlertStep;
use App\Models\StatusAlert;
use App\Models\VaccineRoom;
use App\Models\TargetPublic;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Abstracts\AbstractRepository;
use App\Models\TypeStatusVaccination;
use Illuminate\Database\Eloquent\Model;

class AlertRepository extends AbstractRepository
{

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var TargetPublicRepository
     */
    private $targetPublicRepository;

    /**
     * @var TypeStatusVaccinationsRepository
     */
    private $typeStatusVaccinationsRepository;

    /**
     * @var VaccineRoomRepository
     */
    private $vaccineRoomRepository;

    public function __construct(
        Alert $model,
        TargetPublicRepository $targetPublicRepository,
        TypeStatusVaccinationsRepository $typeStatusVaccinationsRepository,
        VaccineRoomRepository $vaccineRoomRepository
    )
    {
        $this->model = $model;
        $this->targetPublicRepository = $targetPublicRepository;
        $this->typeStatusVaccinationsRepository = $typeStatusVaccinationsRepository;
        $this->vaccineRoomRepository = $vaccineRoomRepository;
    }

    /**
     * @param array $params
     * @return Model
     */
    public function save(array $params): Model
    {
        $params = $this->formatParams($params);
        $alert = $this->getModel()->forceCreate($params);
        return $alert;
    }

    public function formatParams(array $params): array
    {
        $params['is_event'] = 0;
        $params['is_alert'] = 0;
        $params['is_visit'] = 0;

        $typeStatusVaccination = $this->typeStatusVaccinationsRepository->find($params['type_status_vaccination_id']);
        $params['type_status_vaccination_id'] = $typeStatusVaccination->id;
        $params['vaccine_room_id'] = isset($params['vaccine_room_id']) ?
            $this->vaccineRoomRepository->find($params['vaccine_room_id'])->id : null;

        if ($typeStatusVaccination->name == TypeStatusVaccination::TYPE_STATUS[TypeStatusVaccination::VACINACAO_EM_DIA]) {
            $params['is_visit'] = 1;
        } else {
            $params['is_alert'] = 1;
        }

        return [
            'user_id' => $this->getAttribute($params, 'user_id', Auth::id()),
            'record_id' => $this->getAttribute($params, 'record_id'),
            'uuid' => $this->getAttribute($params, 'uuid'),
            'target_public_id' => $this->targetPublicRepository
                ->find($this->getAttribute($params, 'target_public_id'))
                ->id,
            'type_status_vaccination_id' => $this->getAttribute($params, 'type_status_vaccination_id'),
            'name' => $this->getAttribute($params, 'name'),
            'vaccine_room_id' => $this->getAttribute($params, 'vaccine_room_id'),
            'cpf' => $this->getAttribute($params, 'cpf'),
            'rg' => $this->getAttribute($params, 'rg'),
            'birthdate' => $this->getAttribute($params, 'birthdate'),
            'breed_id' => $this->getAttribute($params, 'breed_id'),
            'genre_id' => $this->getAttribute($params, 'genre_id'),
            'suscard' => $this->getAttribute($params, 'suscard'),
            'mother_name' => $this->getAttribute($params, 'mother_name'),
            'mother_cpf' => $this->getAttribute($params, 'mother_cpf'),
            'mother_email' => $this->getAttribute($params, 'mother_email'),
            'mother_rg' => $this->getAttribute($params, 'mother_rg'),
            'mother_phone' => $this->getAttribute($params, 'mother_phone'),
            'mother_mobilephone' => $this->getAttribute($params, 'mother_mobilephone'),
            'father_name' => $this->getAttribute($params, 'father_name'),
            'father_cpf' => $this->getAttribute($params, 'father_cpf'),
            'father_email' => $this->getAttribute($params, 'father_email'),
            'father_rg' => $this->getAttribute($params, 'father_rg'),
            'father_phone' => $this->getAttribute($params, 'father_phone'),
            'father_mobilephone' => $this->getAttribute($params, 'father_mobilephone'),
            'postalcode' => $this->getAttribute($params, 'postalcode'),
            'street' => $this->getAttribute($params, 'street'),
            'address_complement' => $this->getAttribute($params, 'address_complement'),
            'state' => $this->getAttribute($params, 'state'),
            'city' => $this->getAttribute($params, 'city'),
            'district' => $this->getAttribute($params, 'district'),
            'reason_not_has_vac_card_pic' => $this->getAttribute($params, 'reason_not_has_vac_card_pic'),
            'bae' => $this->getAttribute($params, 'bae'),
            'visit_date' => $this->getAttribute($params, 'visit_date'),
            'comments' => $this->getAttribute($params, 'comments'),
            'is_visit' => $this->getAttribute($params, 'is_visit'),
            'is_event' => $this->getAttribute($params, 'is_event'),
            'is_alert' => $this->getAttribute($params, 'is_alert'),
            'county_id' => $this->getAttribute($params, 'county_id'),
        ];
    }
}
