<?php

namespace App\Http\Resources\Configuration;

use App\Models\TargetPublic;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubGroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => !empty($this->uuid) ? $this->uuid : $this->id,
            'name' => $this->name,
            'group_id' => $this->group_id,
            'users' => SubGroupUserResource::collection($this->subGroupUsers),
        ];
    }
}
