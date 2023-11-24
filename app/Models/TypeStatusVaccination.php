<?php

namespace App\Models;

use App\Abstracts\AbstractModel;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TypeStatusVaccination extends AbstractModel
{
    use HasFactory, HasUuid;

    protected $fillable = ['uuid'];
    protected $guarded = ['id'];

    const VACINACAO_EM_DIA = 1;
    const ATRASO_VACINAL = 2;
    const NAO_VACINADA = 3;
    const SEM_CARTEIRINHA = 4;

    const TYPE_STATUS = [
        self::VACINACAO_EM_DIA => 'Vacinação em dia',
        self::ATRASO_VACINAL => 'Atraso Vacinal',
        self::NAO_VACINADA => 'Não Vacinada',
        self::SEM_CARTEIRINHA => 'Sem carteirinha',       
    ];

    public $timestamps = false;

    public function alerts()
    {
        return $this->hasMany(Record::class);
    }
}
