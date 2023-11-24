<?php

namespace App\Http\Resources\Selo;

use App\Http\Resources\CountyResource;
use App\Models\County;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class AccessionSeloResource extends JsonResource
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
            'prefeito' => new UserSeloResource(User::find($this->prefeito_id)),
            'gestor_politico' => new UserSeloResource(User::find($this->gestor_politico_id)),
            'status_gestor_politico' => $this->status_gestor_politico,
            'status_prefeito' => $this->status_prefeito,
        ];
    }
}
