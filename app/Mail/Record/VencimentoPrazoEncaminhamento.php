<?php

namespace App\Mail\Record;

use App\Models\Alert;
use App\Models\Record;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VencimentoPrazoEncaminhamento extends Mailable
{
    use Queueable, SerializesModels;

    protected $record;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Record $record)
    {
        $this->record = $record;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        try {
            $vacinas = $this->record->lastAlert->vaccineScheduledAlerts->toArray();

            $vacinaAtraso = [];

            foreach ($vacinas as $key => $vacina) {
                $vacinaAtraso[$key]['name'] = $vacina['vaccine']['name'];
                $vacinaAtraso[$key]['dose'] = $vacina['vaccine']['dose'];
            }

            $ubs = $this->record->lastAlert->user->county->groups->first()->subGroups->first()->underSubGroups->first();

            $days = 7;

            if ($this->record->lastStepstatus->prazoAnalise['label'] === 'No prazo') {
                $created = new Carbon($this->record->lastStepstatus->created_at);
                $now = Carbon::now()->addDays($this->record->lastStepstatus->prazoAnalise['days']);
                $days = $created->diffInDays($now);
            }

            $params = [
                'nomeCrianca' => $this->record->lastAlert->name,
                'nomeMunicipio' => $this->record->lastAlert->county->name,
                'days' => $days,
                'enderecoUbs' => $ubs->logradouro . ', ' . $ubs->bairro . ', ' . $ubs->endereco,
                'nomeUbs' => $ubs->name,
                'vacinas' => $vacinaAtraso
            ];

            return $this->view('email.record.vencimento-prazo-encaminhamento')
                ->with($params)
                ->subject('BAV |  Seu filho(a) está com vacinas em atraso!');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }

    }
}
