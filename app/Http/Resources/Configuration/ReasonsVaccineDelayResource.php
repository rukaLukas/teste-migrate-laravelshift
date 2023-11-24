<?php

namespace App\Http\Resources\Configuration;

use App\Models\TargetPublic;
use App\Models\TypeReasonDelayVaccine;
use Illuminate\Http\Resources\Json\JsonResource;

class ReasonsVaccineDelayResource extends JsonResource
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
            'id' => !empty($this->uuid) ? $this->uuid : $this->id,
            'description' => $this->description,
            'target_public' => $this->targetPublics->map(function ($item) {
                return [
                    'id' => $item->uuid,
                    'description' => $item->name,
                ];
            }),
            'type_reason_delay_vaccine' => [
                'id' => $this->typeReasonDelayVaccine->uuid,
                'description' => $this->typeReasonDelayVaccine->description,
            ],
            'forwarding' => $this->is_forwarding,                
        ];
    }  
}
