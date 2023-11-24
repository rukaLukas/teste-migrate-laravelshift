<?php

namespace App\Http\Resources;

use App\Models\Alert;
use App\Models\CaseStep;
use App\Models\AlertStep;
use Illuminate\Support\Carbon;
use App\Http\Resources\Configuration\TargetPublicResource;

class LastAlertResource extends AbstractResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->uuid,
            'user_id' => $this->user_id,
            'target_public_id' => $this->target_public_id,
            'breed_id' => $this->breed_id,
            'genre_id' => $this->genre_id,
            'type_status_vaccination_id' => $this->type_status_vaccination_id,
            'record_id' => $this->record_id,
            'name' => $this->name,
            'cpf' => $this->cpf,
            'rg' => $this->rg,
            'birthdate' => $this->birthdate,
            'suscard' => $this->suscard,
            'mother_name' => $this->mother_name,
            'mother_rg' => $this->mother_rg,
            'mother_phone' => $this->mother_phone,
            'mother_mobilephone' => $this->mother_mobilephone,
            'mother_cpf' => $this->mother_cpf,
            'mother_email' => $this->mother_email,
            'father_name' => $this->father_name,
            'father_rg' => $this->father_rg,
            'father_phone' => $this->father_phone,
            'father_mobilephone' => $this->father_mobilephone,
            'father_cpf' => $this->father_cpf,
            'father_email' => $this->father_email,
            'postalcode' => $this->postalcode,
            'street' => $this->street,
            'address_complement' => $this->address_complement,
            'state' => $this->state,
            'city' => $this->city,
            'district' => $this->district,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'reason_not_has_vac_card_pic' => $this->reason_not_has_vac_card_pic,
            'phone' => $this->phone,
            'mobilephone' => $this->mobilephone,
            'bae' => $this->bae,
            'visit_date' => $this->visit_date,
            'visit_date_formatted' => Carbon::create($this->visit_date)->format('d/m/Y') ?? null,
            'comments' => $this->comments,
            'vaccine_room_id' => $this->vaccine_room_id,
            'email' => $this->email,
            'is_alert' => $this->is_alert,
            'is_event' => $this->is_event,
            'is_visit' => $this->is_visit,
            'deleted_at' => $this->deleted_at,
            'uuid' => $this->uuid,
            'county_id' => $this->county_id,
            'vaccine_scheduled_alerts' => $this->vaccineScheduledAlerts,
            'genre' => new GenreResource($this->genre),
            'case_step' => $this->caseStep,
            'county' => new CountyResource($this->county),
            'target_public' => new TargetPublicResource($this->targetPublic),
            'type_status_vaccination' => new TypeStatusVaccinationResource($this->typeStatusVaccination),
            'vaccine_card_pictures' => VaccineCardPictureResource::collection($this->vaccineCardPictures),
            'vaccine_room' => new VaccineRoomResource($this->vaccineRoom),
            'reasons_delay_vaccine' => ReasonDelayVaccineResource::collection($this->reasonDelayVaccines),
            'record' => $this->record,
            'user' => new UserResource($this->user)
        ];
    }
}

