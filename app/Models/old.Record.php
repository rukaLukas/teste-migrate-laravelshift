<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Abstracts\AbstractModel;
use App\Models\ReasonDelayVaccine;
use App\Models\Scopes\RecordAlertScope;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OldRecord extends AbstractModel
{
    use HasFactory, HasUuid, SoftDeletes;

    // protected $table="records";

    protected $guarded = ['id'];

    protected $fillable = [
        'uuid'
        ,'target_public_id'
        ,'name'
        ,'cpf'
        ,'rg'
        ,'birthdate'
        ,'suscard'
        ,'mother_name'
        ,'father_name'
        ,'mother_rg'
        ,'father_rg'
        ,'mother_cpf'
        ,'father_cpf'
        ,'mother_email'
        ,'father_email'
        ,'postalcode'
        ,'street'
        ,'state'
        ,'city'
        ,'district'
    ];    

    public $timestamps = true;

    const MATRICULADA = 1;
    const FORA_DA_ESCOLA = 2;

    const BAE = [
        self::MATRICULADA => 'MATRICULADA',
        self::FORA_DA_ESCOLA => 'FORA DA ESCOLA',        
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function targetPublic()
    {
        return $this->belongsTo(TargetPublic::class);
    }

    public function breed()
    {
        return $this->belongsTo(Breed::class);
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function reasonDelayVaccines()
    {
        return $this->belongsToMany(ReasonDelayVaccine::class);
    }

    public function vaccineCardPictures()
    {
        return $this->hasMany(VaccineCardPicture::class);
    }

    public function vaccineRoom()
    {
        return $this->belongsTo(VaccineRoom::class);
    }

    public function vaccineScheduledAlerts() 
    {        
        return $this->hasMany(VaccineScheduledAlert::class);
    }

    public function typeStatusVaccination() 
    {
        return $this->belongsTo(TypeStatusVaccination::class);
    }

    public function closedAlert()
    {
        return $this->hasOne(ClosedAlert::class);
    }

    public function forwarding()
    {
        return $this->hasOne(Forwarding::class);
    }

    public function pendingAlert()
    {
        return $this->hasOne(PendingAlert::class);
    }

    public function caseStep()
    {
        return $this->hasMany(CaseStep::class);
    }
}
