<?php

namespace App\Http\Resources;

use App\Models\State;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Configuration\GroupResource;

class CountyResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'state_id' => $this->state_id,
            'state' => new StateResource(State::find($this->state_id)),
            'codigo_ibge' => $this->codigo_ibge,
        ];
    }
}
