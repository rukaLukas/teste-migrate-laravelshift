<?php

namespace App\Http\Resources\Configuration;

use App\Models\TargetPublic;
use Illuminate\Http\Resources\Json\JsonResource;

class VaccineResource extends JsonResource
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
        $targetPublic = new TargetPublicResource(TargetPublic::find($this->target_public_id));
        return [
            'id' => !empty($this->uuid) ? $this->uuid : $this->id,
            'name' => $this->name,
            'schema' => $this->schema,
            'dose' => $this->dose,
            'aplication_age_month' => $this->aplication_age_month .' meses',
            'limit_age_year' => 'Menor que '. $this->limit_age_year . ' anos',
            'target_public' => $targetPublic,
            'target_public_name' => $targetPublic->name,
            'days_interval' => $this->days_interval,
            'huma' => $this->created_at_formated,
            'genre' => $this->genre
        ];
    }
}
