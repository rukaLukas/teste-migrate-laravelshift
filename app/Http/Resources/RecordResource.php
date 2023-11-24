<?php

namespace App\Http\Resources;

use App\Models\StatusAlert;
use Illuminate\Support\Facades\DB;
use App\Models\VaccineScheduledAlert;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Configuration\VaccineResource;
use App\Http\Resources\Configuration\DelayedVaccineResource;

class RecordResource extends JsonResource
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
        if (is_null($this->lastAlert)) {
            return null;
        }

        return [
            'id' => $this->uuid ?? $this->id,
            'cpf' => $this->cpf,
            'suscard' => $this->suscard,
            'created_at' => $this->created_at,
            //relacionamentos
            'alerts' => AlertResource::collection($this->alerts),
            'comments' => $this->comments,
            'alert_steps' => $this->alertSteps,
            'status' => $this->last_status,
            'status_text' => $this->last_status != '' ? StatusAlert::find($this->last_status)->name : '',
            'last_alert' => new LastAlertResource($this->lastAlert),
            'etapa_atual' => $this->last_status != '' ? StatusAlert::find($this->last_status)->name : '',
            'created_at_formatted' => $this->created_at_formatted,
            'status_last_alert' => $this->status_last_alert,
            'vaccines_not_applied' => $this->last_status != '' ?
                $this->alertSteps->last()->notAppliedVaccines->map(function ($item) {
                    return [
                        'reason' => $this->getReasonNotAppliedVaccine($item),
                        'vaccine' => new VaccineResource($item->vaccine),
                    ];
                }) : [],
            'delayed_vaccines' => $this->getDelayedVaccines(),
            // 'prazo' => $this->prazo,
            'birthdate_to_human' => $this->birthdate_to_human,
            'history_steps' => $this->history_steps,
            'last_step_status' => $this->last_step_status
        ];

    }

    private function getDelayedVaccines()
    {
        $alertStepFiltered = $this->alertSteps->filter(function ($alertStep) {
            return $alertStep->status_alert_id == StatusAlert::where('name', StatusAlert::STATUS[StatusAlert::ANALISE_TECNICA])->first()->id
                || $alertStep->status_alert_id == StatusAlert::where('name', StatusAlert::STATUS[StatusAlert::ALERTA])->first()->id;
        })->last();


        if ($alertStepFiltered) {
            $delayedVaccines = $alertStepFiltered->delayedVaccines->sortBy(function ($delayedVaccines) {
                return $delayedVaccines->vaccine_id;
            }, SORT_REGULAR, false);

            // filter vaccines by genre
            $delayedVaccines = $delayedVaccines->filter(function ($delayedVaccine) {
                return $delayedVaccine->vaccine->genre == "M/F" || $delayedVaccine->vaccine->genre == "M";
            });
        
            return $delayedVaccines->values()->map(function ($item) {
                $alerts = $this->alerts->pluck('id');
                return [
                    'vaccine' => new DelayedVaccineResource($item->vaccine, $alerts),
                ];
            });
        }

        return [];
    }

    private function getReasonNotAppliedVaccine($item): String
    {
        if (strpos($item->alertStep->reasonNotAppliedVaccine->description, 'Outro') !== false) {
            return $item->alertStep->comments;
        } else {
            return $item->alertStep->reasonNotAppliedVaccine->description;
        }
    }
}
