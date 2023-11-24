<?php

namespace App\Http\Resources\Configuration;

use App\Models\Vaccine;
use App\Models\TargetPublic;
use Illuminate\Support\Carbon;
use App\Models\VaccineScheduledAlert;
use Illuminate\Http\Resources\Json\JsonResource;

class DelayedVaccineResource extends JsonResource
{
    protected $alertsId;
    protected $nextApplication;

    public function __construct($resource, $alertsId)
    {
        parent::__construct($resource);
        $this->resource = $resource;
        $this->alertsId = $alertsId->toArray();
    }

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $resource = VaccineResource::make($this->resource)->toArray($request);
        $resource['is_in_time'] = $this->getTermVaccine($this->alertsId);
        $resource['next_application'] = Carbon::createFromTimeStamp(strtotime($this->nextApplication))->format('d/m/Y');
        return $resource;        
    }

    private function getTermVaccine($alertsId)
    {       
        $previousVaccine = Vaccine::where('name', $this->name)
                ->where('dose', --$this->dose)->first();         
       
        if (!is_null($previousVaccine)) {
            
            $vaccineApplied = VaccineScheduledAlert::whereIn('alert_id', $alertsId)
                ->where('vaccine_id', $previousVaccine->id)
                ->first();
                        
            if (!is_null($vaccineApplied)) { 
                $this->nextApplication = $vaccineApplied->next_application;         
                return $vaccineApplied->next_application < Carbon::now()->format('Y-m-d') ? true : false;
            }  
        }

        return true;
    }
}
