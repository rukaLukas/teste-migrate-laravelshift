<?php

namespace App\Http\Resources\Configuration;

use Illuminate\Http\Resources\Json\JsonResource;

class DeadlineResource extends JsonResource
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
            'days' => $this->days,
        ];
    }
}
