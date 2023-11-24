<?php
namespace App\Http\Resources\Alert;

use Illuminate\Support\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PendingAlertResource extends JsonResource
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
            'id' => $this->id,
            'alert_id' => $this->alert_uuid,
            'name' => $this->nome_crianca,
            'registered_by' => $this->alerta_emitido_por,
            'term' => Carbon::parse($this->prazo_analise)->addDays(env('DEFAULT_TERM_ALERT'))->format('d/m/Y'),
            'vaccine_room' => !is_null($this->sala_vacina) ? $this->sala_vacina : '',
            'target_public' => $this->publico_alvo
        ];
    }
}
