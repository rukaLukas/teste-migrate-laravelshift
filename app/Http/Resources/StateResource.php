<?php

namespace App\Http\Resources;

use App\Http\Resources\Configuration\GroupResource;
use App\Models\Occupation;
use App\Models\Pronoun;
use App\Models\Region;
use Illuminate\Http\Resources\Json\JsonResource;

class StateResource extends JsonResource
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
            'name' => $this->name,
            'region_id' => $this->region_id,
            'region' => new RegionResource(Region::find($this->region_id)),
            'sigla' => $this->sigla,
            'codigo_uf' => $this->codigo_uf
        ];
    }
}
