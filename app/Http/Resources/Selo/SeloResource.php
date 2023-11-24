<?php

namespace App\Http\Resources\Selo;

use Illuminate\Http\Resources\Json\JsonResource;

class SeloResource extends JsonResource
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
            'accessions' => AccessionSeloResource::collection($this->accessions),
            'users' => UserSeloResource::collection($this->users)
        ];
    }
}
