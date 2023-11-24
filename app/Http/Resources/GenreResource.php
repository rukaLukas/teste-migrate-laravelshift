<?php

namespace App\Http\Resources;

class GenreResource extends AbstractResource
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
            'id' => $this->id ?? $this->uuid,
            'name' => $this->name
        ];
    }
}
