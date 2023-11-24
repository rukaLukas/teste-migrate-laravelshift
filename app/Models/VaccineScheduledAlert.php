<?php

namespace App\Models;

use App\Abstracts\AbstractModel;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VaccineScheduledAlert extends AbstractModel
{
    use HasFactory, HasUuid;

    protected $fillable = ['uuid', 'alert_id', 'vaccine_id', 'previous_application', 'next_application'];

    protected $guarded = ['id'];

    public $timestamps = false;

    public function alert()
    {
        return $this->belongsTo(Alert::class);
    }

    public function vaccine()
    {
        return $this->belongsTo(Vaccine::class);
    }
}
