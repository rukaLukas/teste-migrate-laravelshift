<?php

namespace App\Http\Resources;

use Illuminate\Support\Carbon;
use App\Models\StatusAlert;

class StatusAlertResource extends AbstractResource
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
            'id' => $this->uuid,
            'name' => $this->name,            
        ];
    }
}
