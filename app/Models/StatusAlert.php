<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Abstracts\AbstractModel;
use App\Models\ReasonDelayVaccine;
use App\Models\Scopes\RecordAlertScope;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StatusAlert extends AbstractModel
{
    use HasFactory, HasUuid;

    protected $guarded = ['id'];

    protected $fillable = [
        'uuid'
        ,'name'
    ];

    public $timestamps = true;

    public $appends = ['status_formatted', 'which_status'];

    const VISITA = 1;
    const ALERTA = 2;
    const ANALISE_TECNICA = 3;
    const ENCAMINHAMENTO = 4;
    const SALA_VACINA = 5;
    const CONCLUIDO = 6;
    const ENCERRADO = 7;

    const STATUS = [
        self::VISITA => 'Visita',
        self::ALERTA => 'Alerta',
        self::ANALISE_TECNICA => 'Análise Técnica',
        self::ENCAMINHAMENTO => 'Encaminhamento',
        self::SALA_VACINA => 'Sala de Vacina',
        self::CONCLUIDO => 'Concluído',
        self::ENCERRADO => 'Encerrado'
    ];

    public function getStatusFormattedAttribute()
    {
        if ($this->id === self::VISITA) {
            return 'is_visit';
        }
        if ($this->id === self::ALERTA) {
            return 'is_alert';
        }
        if ($this->id === self::ANALISE_TECNICA) {
            return 'is_analysis';
        }
        if ($this->id === self::ENCAMINHAMENTO) {
            return 'is_forwarded';
        }
        if ($this->id === self::SALA_VACINA) {
            return 'is_vaccineroom';
        }
        if ($this->id === self::CONCLUIDO) {
            return 'is_done';
        }
        if ($this->id === self::ENCERRADO) {
            return 'is_closed';
        }
    }

    public function getWhichStatusAttribute()
    {
        return [$this->getStatusFormattedAttribute() => true];
    }


}
