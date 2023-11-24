<?php

namespace App\Http\Resources\Configuration;

use App\Models\TargetPublic;
use Illuminate\Http\Resources\Json\JsonResource;

class UnderSubGroupResource extends JsonResource
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
            'name' => $this->name,
            'logradouro' => $this->logradouro,
            'endereco' => $this->endereco,
            'bairro' => $this->bairro,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'users' => UnderSubGroupUserResource::collection($this->underSubGroupUsers),
        ];
    }
}
