<?php

namespace App\Http\Resources;

use App\Models\Occupation;
use Illuminate\Http\Resources\Json\JsonResource;

class OccupationResource extends JsonResource
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
        $explode = explode(' ', $this->name);
        return [
            'id' => $this->uuid ?? $this->id,
            'name' => $this->name,
            'is_gestor_politico' => $this->id === Occupation::GESTOR_POLITICO,
            'is_prefeito' => $this->id === Occupation::PREFEITO,
            'is_coordenador_operacional' => $this->id === Occupation::COORDENADOR_OPERACIONAL_SAUDE,
            'is_gestor_nacional' => $this->id === Occupation::GESTOR_NACIONAL,
            'is_articulador_municipal' => $this->id === Occupation::ARTICULADOR_MUNICIPAL,
            'abbreviation' => $explode[0] ?? '',
            'permissions' => PermissionResource::collection($this->permissions)
        ];
    }
}
