<?php

namespace App\Models;

use App\Models\Alert;
use App\Traits\HasUuid;
use App\Abstracts\AbstractModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReasonDelayVaccine extends AbstractModel
{
    use HasFactory, HasUuid;

    protected $fillable = ['uuid'];
    protected $guarded = ['id'];

    public function alerts()
    {
        return $this->belongsToMany(Alert::class);
    }

    public function typeReasonDelayVaccine()
    {
        return $this->belongsTo(TypeReasonDelayVaccine::class);
    }   

    public function governmentOffices()
    {
        return $this->belongsToMany(GovernmentOffice::class, 'go_rdv')
            ->as('go_rdv');          
    }

    public function targetPublics()
    {
        return $this->belongsToMany(TargetPublic::class, 'tp_reason_delay_vaccine');
    }
}
