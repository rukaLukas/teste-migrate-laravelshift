<?php

namespace App\Models;

use App\Abstracts\AbstractModel;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Occupation extends AbstractModel
{
    use HasFactory, HasUuid;

    protected $fillable = ['uuid', 'name'];
    protected $guarded = ['id'];
    public $timestamps = true;

    const GESTOR_NACIONAL = 1;
    const GESTOR_POLITICO = 2;
    const COORDENADOR_OPERACIONAL_SAUDE = 3;
    const SUPERVISOR_INSTITUCIONAL_SAUDE = 4;
    const TECNICO_VERIFICADOR = 5;
    const AGENTE_ALERTA = 6;
    const PREFEITO = 7;
    const SUPERVISOR_INSTITUCIONAL_EDUCACAO = 8;
    const SUPERVISOR_INSTITUCIONAL_ASSISTENCIA_SOCIAL = 9;
    const ARTICULADOR_MUNICIPAL = 10;
    const AGENTE_SAUDE = 11;

    public function permissions()
    {
        return $this->hasMany(MenuOccupation::class);
    }

    const OCCUPATIONS = [
        self::GESTOR_NACIONAL => 'Gestor nacional',
        self::GESTOR_POLITICO => 'Gestor(a) político(a)',
        self::COORDENADOR_OPERACIONAL_SAUDE => 'Coordenador(a) operacional da saúde',
        self::SUPERVISOR_INSTITUCIONAL_SAUDE => 'Supervisor(a) institucional da saúde',
        self::TECNICO_VERIFICADOR => 'Técnico verificador',
        self::AGENTE_ALERTA => 'Agente de alerta',
        self::PREFEITO => 'Prefeito',
        self::SUPERVISOR_INSTITUCIONAL_EDUCACAO => 'Supervisor(a) institucional da educação',
        self::SUPERVISOR_INSTITUCIONAL_ASSISTENCIA_SOCIAL => 'Supervisor(a) institucional da assistência social',
        self::ARTICULADOR_MUNICIPAL => 'Articulador municipal',
        self::AGENTE_SAUDE => 'Agente de saúde'
    ];

    const PERMISSIONS = [
        self::GESTOR_NACIONAL => [
            'home',
            'dashboard',
            'accession',
            'records',
            'alerts',
            'events',
            'reports',
            'tools',
            'users',
            'configurations',
            'profile'
        ],
        self::GESTOR_POLITICO => [
            'configurations',
            'home',
            'records',
            'alerts',
            // 'events',
            'dashboard',
            'users',
        ],
        self::COORDENADOR_OPERACIONAL_SAUDE => [
            'configurations',
            'home',
            'records',
            'alerts',
            'events',
            'dashboard',
            'users',
        ],
        self::SUPERVISOR_INSTITUCIONAL_SAUDE => [
            'home',
            'records',
            'alerts',
            'events'
        ],
        self::TECNICO_VERIFICADOR => [
            'home',
            'tools',
            'records',
            'alerts',
            'events',
            //'vaccine-room'
        ],
        self::AGENTE_ALERTA => [
            'home',
            'records',
            'alerts',
        ],
        self::AGENTE_SAUDE => [
            'home',
            'records',
            'alerts',
            //'vaccine_room',
        ],
        self::PREFEITO => [
            'home',
            'dashboard',
            'records',
            'alerts'
        ],
        self::SUPERVISOR_INSTITUCIONAL_EDUCACAO => [
            'home',
            'records',
            'alerts'
        ],
        self::SUPERVISOR_INSTITUCIONAL_ASSISTENCIA_SOCIAL => [
            'home',
            'records',
            'alerts'
        ],
        self::ARTICULADOR_MUNICIPAL => [
            'home',
            'users',
            'accession',
        ]
    ];
}
