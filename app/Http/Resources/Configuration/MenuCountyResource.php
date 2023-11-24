<?php

namespace App\Http\Resources\Configuration;

use App\Http\Resources\CountyResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuCountyResource extends JsonResource
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
            'county_id' => $this->county_id,
            'county' => new CountyResource($this->county),
        ];
    }
}
