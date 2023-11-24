<?php

namespace App\Http\Resources;

use App\Models\Alert;
use App\Models\Vaccine;
use App\Models\CaseStep;
use App\Models\AlertStep;
use Illuminate\Support\Carbon;
use App\Models\VaccineScheduledAlert;
use App\Http\Resources\Configuration\TargetPublicResource;

class AlertResource extends AbstractResource
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
        // order vaccineScheduledAlerts by vaccine_id
        $vaccineScheduledAlerts = $this->vaccineScheduledAlerts->sortBy(function ($vaccineScheduledAlert) {
            return $vaccineScheduledAlert->vaccine_id;
        });

        return [
            'id' => $this->uuid,
            'record_id' => $this->record->uuid,
            'target_public' => new TargetPublicResource($this->targetPublic),
            'type_status_vaccination' => new TypeStatusVaccinationResource($this->typeStatusVaccination), //$this->typeStatusVaccination,
            'vaccine_scheduled_alerts' => VaccineScheduledAlertResource::collection($vaccineScheduledAlerts),
            'vaccines_to_applly' => $this->getVaccinesToBeApplied(),
            'vaccine_room' => $this->vaccineRoom,
            'name' => $this->name,
            'mobilephone' => $this->mobilephone,
            'phone' => $this->phone,
            'cpf' => !empty($this->cpf) ? $this->mask($this->cpf, '###.###.###-##') : "",
            'rg' => !is_null($this->rg) ? $this->mask($this->rg, '##.###.####-##') : null,
            'birthdate' => $this->birthdate,
            'birthdate_formatted' => Carbon::parse($this->birthdate)->format('d/m/Y'),
            'birthdate_to_human' => str_replace(
                'há ',
                '',
                Carbon::parse($this->birthdate)->diffForHumans(null, [
                    'parts' => 2,
                    'join' => ' e ',
                ])
            ),
            'breed' => $this->breed,
            'genre' => $this->genre,
            'suscard' => !empty($this->suscard) ? $this->mask($this->suscard, '######-##') : null,
            'mother_name' => $this->mother_name,
            'mother_email' => $this->mother_email,
            'mother_cpf' => $this->mask($this->mother_cpf, '###.###.###-##'),
            'mother_rg' => !is_null($this->mother_rg) ? $this->mask($this->mother_rg, '##.###.####-##') : null,
            'mother_phone' => !is_null($this->mother_phone) ? $this->mask($this->mother_phone, '(##) ####-####') : null,
            'mother_mobilephone' => !is_null($this->mother_mobilephone) ? $this->mask($this->mother_mobilephone, '(##) #####-####') : null,
            'father_name' => $this->father_name,
            'father_email' => $this->father_email,
            'father_cpf' => !is_null($this->father_cpf) ? $this->mask($this->father_cpf, '###.###.###-##') : null,
            'father_rg' => !is_null($this->father_rg) ? $this->mask($this->father_rg, '##.###.####-##') : null,
            'father_phone' => !is_null($this->father_phone) ? $this->mask($this->father_phone, '(##) ####-####') : null,
            'father_mobilephone' => !is_null($this->father_mobilephone) ? $this->mask($this->father_mobilephone, '(##) #####-####') : null,
            'postalcode' => $this->mask($this->postalcode, '#####-###'),
            'street' => $this->street,
            'address_complement' => $this->address_complement,
            'state' => $this->state,
            'city' => $this->city,
            'district' => $this->district,
            'reasons_delay_vaccine' => ReasonDelayVaccineResource::collection($this->reasonDelayVaccines),
            'reason_not_has_vac_card_pic' => $this->reason_not_has_vac_card_pic,
            'vaccine_card_pictures' => VaccineCardPictureResource::collection($this->vaccineCardPictures),
            'bae' => $this->bae,
            'visit_date' => $this->visit_date ?? null,
            'visit_date_formatted' => Carbon::create($this->visit_date)->format('d/m/Y') ?? null,
            'comments' => $this->comments,
            'etapa' => $this->getCurrentStage(),
            'status' => $this->getStatus(),
            'prazo' => '',
            'is_visit' => $this->is_visit,
            'is_alert' => $this->is_alert,
            'is_forwarded' => $this->is_forwarded,
            'is_concluded' => $this->is_concluded,
            'steps' => AlertStepResource::collection($this->record->alertSteps),
            'duration' => $this->getDuration(),
            'county_id' => $this->county_id,
            'who' => [
                'id' => $this->user->uuid ?? '',
                'name' => $this->user->name ?? '',
                'avatar' => '',
                'role' => $this->user->occupation->name ?? '',
            ],
        ];
    }

    private function getVaccinesToBeApplied(): array
    {
        $vaccines = array();
        $idsToExclude = [];
        $vacScheduledAlerts = $this->vaccineScheduledAlerts;                
        $this->vaccineScheduledAlerts->reverse()->map(function ($vaccineScheduledAlert) use (&$vaccines, &$idsToExclude, &$vacScheduledAlerts) {
            // maximo 3 doses schema vacinal
            if ($vaccineScheduledAlert->vaccine->dose != 3) {
                // get all others vaccine dose from this vaccine                
                $nextVaccine = Vaccine::where('name', $vaccineScheduledAlert->vaccine->name)
                    ->where('dose', '!=', $vaccineScheduledAlert->vaccine->dose)
                    ->where('dose', '>', $vaccineScheduledAlert->vaccine->dose)
                    ->where('target_public_id', $this->target_public_id)
                    ->get();
            
                foreach ($nextVaccine as $valueNextVaccine) {
                    if (in_array($valueNextVaccine->id, $idsToExclude))
                        continue;

                    if (array_search($valueNextVaccine->id, array_column($vacScheduledAlerts->toArray(), 'vaccine_id')) !== false)
                        $idsToExclude[] = $valueNextVaccine->id;
                }

                $nextVaccine = $nextVaccine->filter(function ($value) use ($idsToExclude) {
                    return !in_array($value->id, $idsToExclude);
                });

                $nextVaccine->filter(function ($value2) use ($vaccineScheduledAlert, &$vaccines, &$idsToExclude) {
                    // if (($value['vaccine_id'] !== $value2->id) && ($value->vaccine->name == $value2->name)) {
                        $idsToExclude[] = $value2->id;
                        $vaccines[$value2->name][] = $this->getExpiredVaccine($value2, $vaccineScheduledAlert, $vaccines);
                    // }
                });
            }
        });


        return $vaccines;
    }

    private function getExpiredVaccine(mixed $vaccine, mixed $vaccineScheduledAlert, &$vaccines): mixed
    {
        $currentDate = Carbon::create('now');
        if (!is_null($vaccineScheduledAlert->next_application)) {
            $nextApplicationDate = Carbon::create($vaccineScheduledAlert->next_application);
        } else {
            $previousApplication = Carbon::create($vaccineScheduledAlert->previous_application);
            $nextApplicationDate = $previousApplication->addDays($vaccineScheduledAlert->vaccine->days_interval);
        }

        $vaccine['expired'] = $nextApplicationDate->lt($currentDate);
        $vaccine['next_application'] = $nextApplicationDate->format('d/m/Y');
        return $vaccine;
    }

    private function getCurrentStage(): string
    {
        $case = CaseStep::where('alert_id', $this->id)->get()->last();
        $stages = ['is_alert', 'is_analysis', 'is_forwarded', 'is_vaccineroom', 'is_done', 'is_closed'];
        $possibleReturns = [
            'is_alert' => 'Alerta',
            'is_analysis' => 'Análise',
            'is_forwarded' => 'Encaminhado',
            'is_vaccineroom' => 'Sala de Vacina',
            'is_done' => 'Concluído',
            'is_closed' => 'Encerrado',
        ];

        if (!is_null($case)) {
            foreach ($stages as $stage) {
                if ($case->$stage == 1)
                    return $possibleReturns[$stage];
            }
        }

        if (is_null($case)) {
            if ($this->is_visit == 1)
                return 'Visita';
            else if ($this->is_alert == 1)
                return 'Alerta';
        }
    }

    private function getStatus(): string
    {
        $case = CaseStep::where('alert_id', $this->id)->get()->last();
        if ($this->is_alert == 1 && is_null($case)) {
            return 'Pendente de Análise';
        }

        if ($this->is_visit == 1) {
            $numVisits = Alert::where('cpf', $this->cpf)->count();
            return  $numVisits . 'ª visita';
        }
    }

    private function getDuration()
    {
        $begin = AlertStep::where('record_id', $this->record_id)->first();
        $last = AlertStep::where('record_id', $this->record_id)->orderBy('created_at', 'desc')->first();
        $duration = Carbon::parse($begin->created_at)->diffInDays(Carbon::parse($last->created_at)) + 1;

        return $duration;
    }
}
