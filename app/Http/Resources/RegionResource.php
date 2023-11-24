<?php

namespace App\Http\Resources;

use App\Http\Resources\Configuration\GroupResource;
use App\Models\Occupation;
use App\Models\Pronoun;
use Illuminate\Http\Resources\Json\JsonResource;

class RegionResource extends JsonResource
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
        ];
    }
}
