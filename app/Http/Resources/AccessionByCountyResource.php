<?php

namespace App\Http\Resources;

use App\Models\County;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class AccessionByCountyResource extends JsonResource
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
            'county' => new CountyResource(County::find($this->county_id)),
            'county_id' => $this->county_id,
            'prefeito_id' => $this->prefeito_id,
            'gestor_politico_id' => $this->gestor_politico_id,
            'status_gestor_politico' => $this->status_gestor_politico,
            'status_prefeito' => $this->status_prefeito,
            'status' => $this->status,
            'pendencies' => $this->pendencies,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_at_to_human' => $this->created_at_to_human,
            'created_at_formated' => $this->created_at_formated,
        ];
    }
}
