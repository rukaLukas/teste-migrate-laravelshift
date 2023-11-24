<?php

namespace App\Http\Resources\Configuration;

use App\Models\User;
use App\Models\TargetPublic;
use App\Models\TypeReasonDelayVaccine;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ReasonDelayVaccineResource;

class ReasonsVaccineDelayUserResource extends JsonResource
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
            'description' => $this->description,
            'target_public' => $this->targetPublics->map(function ($item) {
                return [
                    'id' => $item->uuid,
                    'description' => $item->name,
                ];
            }),
            'type_reason_delay_vaccine' => [
                'id' => $this->typeReasonDelayVaccine->uuid,
                'description' => $this->typeReasonDelayVaccine->description,
            ],
            'forwarding' => $this->getGovernmentOffices()                         
        ];
    }

    private function getGovernmentOffices()
    {
        $ret = [];
        foreach ($this->governmentOffices as $value) {
            $ret[] = [
                'id' => $value->uuid,
                'name' => $value->name,
                'email' => $value->email,
                // 'user_id' => User::find($value->user_id)->uuid,
            ];
        }
        return $ret;            
    }
}
