<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Abstracts\AbstractModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotAppliedVaccine extends AbstractModel
{
    use HasFactory, HasUuid;

    protected $guarded = ['id'];

    protected $fillable = [
        'uuid'
        ,'vaccine_id'
        ,'alert_step_id'
        ,'reason_not_applied_vaccine_id'        
    ];

    public $timestamps = true;

    public function vaccine()
    {
        return $this->belongsTo(Vaccine::class);
    }

    public function alertStep()
    {
        return $this->belongsTo(AlertStep::class);
    }
}
