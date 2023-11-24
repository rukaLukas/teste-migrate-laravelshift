<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VaccineRoomResource extends JsonResource
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
            'postalcode' => $this->postalcode,
            'street' => $this->street,
            'state' => $this->state,
            'city' => $this->city,
            'district' => $this->district
        ];
    }
}
