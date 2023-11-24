<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Configuration\GovernmentOfficeResource;

class ReasonDelayVaccineResource extends JsonResource
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
            'target_public' => $this->target_public_id,
            'description' => $this->description,
            'is_send_social_assistence' => (bool)$this->is_send_social_assistence,
            'forwarding' =>  GovernmentOfficeResource::collection($this->governmentOffices)
        ];
    }
}
