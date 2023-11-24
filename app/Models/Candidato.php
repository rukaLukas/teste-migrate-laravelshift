<?php

namespace App\Models;

use App\Abstracts\AbstractModel;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Candidato extends AbstractModel
{
    use HasFactory;  
    
    protected $table="candidatos";

    protected $fillable = ['DT_GERACAO', 'ANO_ELEICAO', 'NR_TURNO', 'SG_UF', 'SG_UE', 'NM_UE', 'CD_CARGO', 'SQ_CANDIDATO', 'NM_CANDIDATO', 'NR_CPF_CANDIDATO', 'DS_SITUACAO_CANDIDATURA', 'CD_SITUACAO_CANDIDATURA'];
    protected $guarded = ['id'];
    public $timestamps = true;    
}
