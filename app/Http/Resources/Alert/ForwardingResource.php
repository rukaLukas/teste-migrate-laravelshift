<?php
namespace App\Http\Resources\Alert;

use Illuminate\Http\Resources\Json\JsonResource;

class ForwardingResource extends JsonResource
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
            'alert_id' => $this->alert->uuid,
            'user_id' => $this->user->uuid,
            'description' => $this->description,
            'email' => $this->email,
            'users_total' => 10
        ];
    }
}
