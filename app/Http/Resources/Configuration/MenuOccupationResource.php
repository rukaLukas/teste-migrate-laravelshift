<?php

namespace App\Http\Resources\Configuration;

use App\Http\Resources\CountyResource;
use App\Http\Resources\OccupationResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuOccupationResource extends JsonResource
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
            'menu_id' => $this->menu_id,
            'menu' => new MenuResource($this->menu),
            'occupation_id' => $this->occupation_id,
            'occupation' => new OccupationResource($this->occupation),
        ];
    }
}
