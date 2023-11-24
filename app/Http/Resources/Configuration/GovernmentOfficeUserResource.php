<?php

namespace App\Http\Resources\Configuration;

use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class GovernmentOfficeUserResource extends JsonResource
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
            'user' => new UserResource($this->user),
            'governmentOffice' => [
                'id' => !empty($this->governmentOffice->uuid) ? $this->governmentOffice->uuid : $this->governmentOffice->id,
                'name' => $this->governmentOffice->name,
                'email' => $this->governmentOffice->email,                
            ]
        ];
    }
}
