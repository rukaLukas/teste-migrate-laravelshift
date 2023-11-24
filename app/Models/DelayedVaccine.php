<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Abstracts\AbstractModel;
use App\Models\ReasonDelayVaccine;
use App\Models\Scopes\RecordAlertScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class DelayedVaccine extends AbstractModel
{
    use HasFactory, HasUuid;

    protected $guarded = ['id'];

    protected $fillable = [
        'uuid',
        'alert_step_id',
        'vaccine_id',     
    ];

    public $timestamps = true;

    public function alertStep()
    {
        return $this->belongsTo(AlertStep::class);
    }

    public function vaccine()
    {
        return $this->belongsTo(Vaccine::class);
    }
}
