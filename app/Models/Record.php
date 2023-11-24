<?php

namespace App\Models;

use Carbon\Carbon;
use App\Traits\HasUuid;
use Illuminate\Support\Arr;
use App\Abstracts\AbstractModel;
use App\Services\AlertStepService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Record extends AbstractModel
{
    use HasFactory, HasUuid;

    private $steps = [];

    protected $guarded = ['id'];

    protected $fillable = [
        'uuid',
        'cpf',
        'suscard',
    ];

    protected $appends = [
        'history_steps',
        'last_step_status'
    ];

    public $timestamps = true;

    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }

    public function forwarding()
    {
        return $this->hasOne(Forwarding::class);
    }

    public function lastAlert()
    {
        $countyId = Auth::user()->county_id ?? null;
        $query = $this->hasOne(Alert::class)
            ->with([
                'vaccineScheduledAlerts',
                'genre',
                'caseStep',
                'county',
                'targetPublic',
                'typeStatusVaccination',
                'vaccineCardPictures',
                'vaccineRoom',
                'record',
                'user',
            ]);
        if ($countyId) {
            $query->where('county_id', '=', $countyId);
        }
        $query->latest('id');
        return $query;
    }

    public function comments()
    {
        return $this->hasMany(CommentRecord::class)->with(['user', 'user.occupation']);
    }

    public function alertSteps()
    {
        return $this->hasMany(AlertStep::class)->with(['statusAlert']);
    }

    private function lastStepStatus($recordId)
    {
        return AlertStep::with(['statusAlert', 'reasonCloseAlert', 'user'])
            ->where(['record_id' => $recordId])
            ->latest('id')
            ->first();
    }

    public function getLastStepStatusAttribute()
    {
        return $this->lastStepStatus($this->id);
    }

    public function getBirthdateToHumanAttribute()
    {
        $lastAlert = $this->lastAlert()->first();
        if ($lastAlert) {
            return Carbon::createFromTimeStamp(strtotime($lastAlert->birthdate))->longAbsoluteDiffForHumans();
        }
        return '';
    }

    public function getStatusLastAlertAttribute()
    {
        $lastAlert = $this->lastAlert()->first();
        if ($lastAlert) {
            $statusLastAlert = $this->lastStepStatus($lastAlert->record_id)->status_alert_id;
            foreach (StatusAlert::STATUS as $key => $statusAlert) {
                if ($statusLastAlert === $key
                    && ($key === StatusAlert::VISITA || $key === StatusAlert::ENCAMINHAMENTO)
                ) {
                    $numberStatusAlert = $this->getNumberStatusAlert($lastAlert, $key);
                    return $numberStatusAlert . 'º ' . $statusAlert;
                }
                if ($key === StatusAlert::ALERTA && $statusLastAlert === $key) return 'Pendente de Análise';
            }
        }
        return '---';
    }

    private function getNumberStatusAlert(Alert $lastAlert, int $typeStatusAlert = 1)
    {
        $statusLastAlert = $this->lastStepStatus($lastAlert->record_id)->status_alert_id;
        $typeStatusAlert = StatusAlert::where('name', StatusAlert::STATUS[$typeStatusAlert])->first()->id;
        if ($statusLastAlert === $typeStatusAlert) {
            $statusLastAlert = AlertStep::where([
                'record_id' => $lastAlert->record_id,
                'status_alert_id' => $statusLastAlert
            ])
                ->orderBy('created_at', 'desc')
                ->get();
            return count($statusLastAlert->toArray());
        }

        return 0;
    }

    public function getLastStatusAttribute()
    {
        $statusAtual = $this->lastStepStatus($this->id);
        if ($statusAtual) {
            return $statusAtual->status_alert_id;
        }
        return '';
    }

    public function getTermLastStatusAttribute()
    {
        $statusAtual = $this->lastStepStatus($this->id);
        if ($statusAtual) {
            return StatusAlert::STATUS[$statusAtual->status_alert_id];
        }
        return '';
    }

    /**
     * @return string
     */
    public function getCreatedAtFormattedAttribute()
    {
        return Carbon::createFromTimeStamp(strtotime($this->created_at))->format('d/m/Y');
    }

    /**
     * Faz o histórico na tela de events/visits (alerta)
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getHistoryStepsAttribute(): array
    {
        $this->steps = $this->alertSteps()->get()->toArray();
        $this
            ->makeTitle()
            ->makePastAndCurrent()
            ->makeHistoryFutureSteps()
            ->makeHistory()
            ->makeCompleteSteps();
        return $this->steps;
    }

    /**
     * Adiciona a etapa encerrado, se não existir
     * @return $this
     */
    private function makeCompleteSteps(): self
    {
        $steps = $this->steps;
        sort($steps);
        $index = array_search(StatusAlert::ENCERRADO, array_column($steps, 'status_id'));
        if (false !== $index) {
            $statusEncerrado = $steps[$index];
            unset($steps[$index]);
            array_push($steps, $statusEncerrado);
        }
        $this->steps = $steps;
        return $this;
    }

    /**
     * Monta o histórico, retornando só o necessário para montar o componente
     * @return Record
     */
    private function makeHistory(): self
    {
        $resourceSteps = [];
        foreach ($this->steps as $key => $step) {
            $nameStep = $step['name'] ?? $step['status_alert']['name'];
            $userId = $step['user_id'] ?? null;
            $resourceSteps[] = [
                'id' => $key,
                'id_disabled' => $step['id_disabled'] ?? false,
                'is_future' => $step['is_future'] ?? false,
                'is_current' => $step['is_current'] ?? false,
                'is_past' => $step['is_past'] ?? false,
                'name' => $nameStep,
                'title' => empty($step['title']) ? ucwords($step['name']) : $step['title'],
                'subtitle' => Carbon::make($step['created_at'])->format('d/m/Y'),
                'user_id' => $step['user_id'] ?? null,
                'who' => $this->getWho($userId),
                'status_id' => $step['status_alert_id'] ?? $step['id']
            ];
        }
        //Se for visita, não aparece como etapa atual
        $this->steps = array_filter($resourceSteps, fn($step) => $step['status_id'] !== StatusAlert::VISITA);
        return $this;
    }

    private function getWho(?int $userId): ?array
    {
        $who = null;
        if (!is_null($userId)) {
            $user = User::find($userId);
            $who = array(
                'id' => $user->uuid,
                'name' => $user->name,
                'avatar' => $user->photo_url,
                'role' => $user->occupation->name,
            );
        }
        return $who ?? null;
    }

    /**
     * Faz os títulos de acordo com a quantidade
     * Ex: 2º encaminhamento.
     * @return $this
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function makeTitle(): self
    {
        $alertStepService = app()->make(AlertStepService::class);
        foreach ($this->steps as $key => $step) {
            $name = ucwords($step['status_alert']['name']);
            $count = $alertStepService->getRepository()->where(
                [
                    ['id', '<=', $step['id']],
                    ['status_alert_id', '=', $step['status_alert_id']],
                    ['record_id', '=', $step['record_id']]
                ]
            )->count();
            $this->steps[$key]['title'] = $count <= 1 ? $name : $count . "º " . $name;
        }
        return $this;
    }

    /**
     * Decide se a situação é passado ou atual
     * @return $this
     */
    private function makePastAndCurrent(): self
    {
        $steps = $this->steps;
        foreach ($steps as $key => $step) {
            $steps[$key]['is_future'] = false;
            $steps[$key]['is_current'] = false;
            $steps[$key]['is_past'] = true;
            if ($key + 1 === count($steps)) {
                $steps[$key]['is_past'] = false;
                $steps[$key]['is_current'] = true;
            }
        }
        $this->steps = $steps;
        return $this;
    }

    /**
     * Separa apenas os steps futuros.
     * @return $this
     */
    private function makeHistoryFutureSteps(): self
    {
        $statusAll = StatusAlert::all();
        $steps = $this->steps;
        foreach ($statusAll as $status) {
            $is = array_search($status->id, array_column($steps, 'status_alert_id'));
            if (false === $is) {
                if ($status->id !== StatusAlert::ENCERRADO) {
                    array_push($steps, array_merge($status->toArray(), ['is_future' => true]));
                }

            }
        }
        $this->steps = $steps;
        return $this;
    }

    public function scopeQuery(Builder $queryBuilder, $params = []): Builder
    {
        $recordFilter = app()->make('App\Models\Scopes\Record\RecordFilter');
        return $recordFilter->filter($queryBuilder, $params);
    }
}
