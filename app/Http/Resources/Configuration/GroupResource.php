<?php

namespace App\Http\Resources\Configuration;

use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
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
            'users' => GroupUserResource::collection($this->groupUsers),
            'subGroups' => $this->subGroups,
            'county_id' => $this->county_id
        ];
    }
}
