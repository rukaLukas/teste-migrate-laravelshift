<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Models\Record;
use App\Models\Deadline;
use Illuminate\Support\Carbon;

class AlertStepResource extends AbstractResource
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
            'record_id' => Record::find($this->record_id)->uuid,
            'status_alert' => [
                'id' => $this->status_alert_id,
                'status' => $this->statusAlert->name,
                'status_formatted' => $this->statusAlert->status_formatted
            ],
            'user_id' => User::find($this->user_id)->uuid,
            'comments' => $this->comments,
            'created' => $this->created_at,
            'term' => $this->getTerm()['days_to_term'],
            'term_date' => $this->getTerm()['term_date'],
        ];
    }

    private function getTerm()
    {
        $deadlineName = '';
        switch ($this->statusAlert->getStatusFormattedAttribute()) {
            case 'is_alert':
                $deadlineName = 'Alerta';
                break;
            case 'is_analysis':
                $deadlineName = 'Análise técnica';
                break;
            case 'is_forwarded':
                $deadlineName = 'Encaminhamentos';
                break;
            case 'is_vaccineroom':
                $deadlineName = 'Sala de vacina';
                break;
        }

        if (!empty($deadlineName)) {
            $deadlineDays = Deadline::where('name', $deadlineName)->get('days')->first()->days ?? 15;
            $termDate = Carbon::parse($this->created_at->format('Y-m-d'))->addDays($deadlineDays);
            $diffInDays = Carbon::parse(Carbon::now())->diffInDays($termDate) + 1;

            return [
                'days_to_term' => $diffInDays,
                'term_date' => $termDate->format('Y-m-d H:i:s')
            ];
        }

        return [
                'days_to_term' => '',
                'term_date' => ''
            ];
    }
}
