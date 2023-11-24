<?php

namespace App\Http\Resources\Configuration;

use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class GovernmentOfficeResource extends JsonResource
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
            'email' => $this->email,
            'type' => $this->type,
            // 'governmentOfficeUsers' => GovernmentOfficeUserResource::collection($this->governmentOfficeUsers),
            // 'users_total' => count($this->governmentOfficeUsers)
        ];
    }
}
