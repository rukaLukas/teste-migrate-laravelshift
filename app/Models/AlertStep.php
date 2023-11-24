<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Support\Carbon;
use App\Abstracts\AbstractModel;
use App\Models\ReasonDelayVaccine;
use App\Models\Scopes\RecordAlertScope;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AlertStep extends AbstractModel
{
    use HasFactory, HasUuid;

    protected $guarded = ['id'];

    protected $fillable = [
        'uuid'
        ,'record_id'
        ,'status_alert_id'
        ,'user_id',
        'reason_close_alert_id',
        'comments',
        'reason_not_applied_vaccine_id',
    ];

    protected $appends = [
        'last_status',
        'prazo_analise'
    ];

    public $timestamps = true;

    public function record()
    {
        return $this->belongsTo(Record::class);
    }

    public function statusAlert()
    {
        return $this->belongsTo(StatusAlert::class);
    }

    public function delayedVaccines()
    {
        return $this->hasMany(DelayedVaccine::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reasonCloseAlert()
    {
        return $this->belongsTo(ReasonCloseAlert::class);
    }

    public function reasonNotAppliedVaccine()
    {
        return $this->belongsTo(ReasonNotAppliedVaccine::class);
    }

    public function notAppliedVaccines()
    {
        return $this->hasMany(NotAppliedVaccine::class);
    }

    public function getLastStatusAttribute()
    {
        $statusAtual = $this->lastStatus($this->record_id);
        return $statusAtual->status_alert_id;
    }

    public function getPrazoAnaliseAttribute()
    {
        // $dateLastAlert = $lastAlert->created_at;
        $daysDeadLine = $this->getDaysDeadline();
        $daysDeadLine = [
            'Alerta' => $daysDeadLine['daysAlert'],
            'Análise Técnica' => $daysDeadLine['daysTechnicalAnalysis'],
            'Encaminhamento' => $daysDeadLine['daysForwarding'],
            'Sala de Vacina' => $daysDeadLine['daysVaccineRoom'],
        ];

        $statusLastAlert = $this->status_alert_id;
        $statusLastAlert = ucfirst(StatusAlert::find($statusLastAlert)->name);
        if ($statusLastAlert === StatusAlert::STATUS[StatusAlert::VISITA] ||
            $statusLastAlert === StatusAlert::STATUS[StatusAlert::CONCLUIDO] ||
            $statusLastAlert === StatusAlert::STATUS[StatusAlert::ENCERRADO]) {
            return '- - -';
        }
        // a data de referencia para calcular o prazo deve ser a data de última etapa(step) do alerta
        else {
            foreach(StatusAlert::STATUS as $key => $statusAlert) {
                if (strtolower($statusLastAlert) === strtolower(StatusAlert::STATUS[$key])) {
                    return $this->getPrazoFromStatus($statusLastAlert, $statusAlert, $daysDeadLine[$statusAlert]);
                }
            }
        }
    }

    private function getPrazoFromStatus(string $statusLastAlert, string $statusAlert, int $days)
    {
        $dateStepAlert = $this->created_at;
        $dateCompare = Carbon::createFromDate($dateStepAlert)->addDay($days);
        $days = Carbon::parse($this->created_at)->addDays($days)->diffInDays();
        return [
            'label' => ($dateCompare->isAfter('now')) ? 'No prazo' : 'Em atraso',
            'days' => !($dateCompare->isAfter('now')) ? $days + 1 : $days
        ];
    }

    public function getTermLastStatusAttribute()
    {
        $statusAtual = $this->lastStatus($this->record_id);
        return StatusAlert::STATUS[$statusAtual->status_alert_id];
    }

    private function lastStatus($recordId)
    {
        return $this
            ->where(['record_id' => $recordId])
            ->latest()
            ->first();
    }

    private function getDaysDeadline(): array
    {
        $numberStatusAlertForwarding = $this->getNumberStatusAlert($this->record_id, StatusAlert::ENCAMINHAMENTO);
        $extensionTermDays = 0;

        $alertSteps = AlertStep::where(['record_id' => $this->record_id])->get();
        // get days_of_interval from delayed_vaccines
        $alertStepFiltered = $alertSteps->filter(function ($alertStep) {
            return $alertStep->status_alert_id == StatusAlert::where('name', StatusAlert::STATUS[StatusAlert::ANALISE_TECNICA])->first()->id;
        })->last();

        if ($alertStepFiltered) {
            $delayedVaccines = $alertStepFiltered->delayedVaccines->sortBy(function ($delayedVaccine) {
                return $delayedVaccine->vaccine->days_interval;
            }, SORT_REGULAR, false);

            $extensionTermDays = ($numberStatusAlertForwarding > 1) ? $delayedVaccines->first()->vaccine->days_interval : 0;
        }

        $daysForwarding = Deadline::where(['name' => Deadline::ENCAMINHAMENTOS])->first()->days ?? 15;

        return [
            'daysAlert' => Deadline::where(['name' => Deadline::ALERTA])->first()->days ?? 15,
            'daysTechnicalAnalysis' => Deadline::where(['name' => Deadline::ANALISE_TECNICA])->first()->days ?? 15,
            'daysForwarding' => $daysForwarding + $extensionTermDays,
            'daysVaccineRoom' => Deadline::where(['name' => Deadline::SALA_DE_VACINA])->first()->days ?? 15
        ];

        // return [];
    }

    /**
     * getNumberStatusAlert function
     *
     * Return number of registers for an specific status alert.
     * Sample 2 registers with status alert forwarding for this record_id
     *
     * @param Alert $lastAlert
     * @param integer $typeStatusAlert
     * @return void
     */
    private function getNumberStatusAlert(int $recordId, int $typeStatusAlert = 1)
    {
        // $statusLastAlert = $this->lastStepStatus($recordId)->status_alert_id;
        // $statusLastAlert = AlertStep::with(['statusAlert', 'reasonCloseAlert', 'user'])
        //     ->latest('id')
        //     ->first();
        // dd($statusLastAlert->toArray());

        $statusLastAlert = $this->status_alert_id;
        $typeStatusAlert = StatusAlert::where('name', StatusAlert::STATUS[$typeStatusAlert])->first()->id;
        if ($statusLastAlert === $typeStatusAlert) {
            $x = AlertStep::where([
                'record_id' => $this->record_id,
                'status_alert_id' => $statusLastAlert
            ])
            ->orderBy('created_at', 'desc')
            ->get();

            // AlertStep::where(['record_id' => $this->record_id])->get();
            $contador = 0;
            foreach($x as $y) {
                $contador++;
            }
            return $contador;
        }

        return 0;
    }

}
