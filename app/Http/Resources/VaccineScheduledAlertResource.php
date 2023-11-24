<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Configuration\VaccineResource;
use App\Models\Alert;

class VaccineScheduledAlertResource extends JsonResource
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
            'id' => $this->uuid ?? $this->id,
            'alert_id' => $this->alert()->first()->uuid ?? $this->alert()->first()->id,
            'vaccine' => new VaccineResource($this->vaccine),
            'vaccination_step' => $this->vaccination_step,
            'previous_application' => $this->previous_application,
            'next_application' => $this->next_application,
        ];
    }
}
