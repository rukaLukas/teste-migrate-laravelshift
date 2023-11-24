<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CaseStepResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->uuid,
            'alert_id' => $this->alert->uuid,
            'user' => is_null($this->user) ? null : 
                [
                    'id' => $this->user->uuid,
                    'name' => $this->user->name,
                    'email' => $this->user->email
                ],
            'is_alert' => $this->is_alert,
            'is_analysis' => $this->is_analysis,
            'is_forwarded' => $this->is_forwarded,
            'is_vaccineroom' => $this->is_vaccineroom,
            'is_done' => $this->is_done,
            'is_closed' => $this->is_closed
        ];
    }
}
