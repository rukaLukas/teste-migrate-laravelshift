<?php

namespace App\Http\Resources\Selo;

use App\Models\Occupation;
use Illuminate\Http\Resources\Json\JsonResource;

class OccupationSeloResource extends JsonResource
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
            'name' => $this->name,
        ];
    }
}
