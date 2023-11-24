<?php
namespace App\Http\Resources\Alert;

use App\Models\Record;
use Illuminate\Support\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class AgentAlertListResource extends JsonResource
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
            'record_id' => Record::find($this->record_id)->uuid,
            'name' => $this->name,
            'cpf' => $this->cpf,
            'suscard' => $this->suscard,
            'current_stage' => $this->current_stage,
            'status' => $this->status,
            'term' => $this->term,
            'created_at' => Carbon::parse($this->created_at)->format('d/m/Y')
        ];
    }
}
