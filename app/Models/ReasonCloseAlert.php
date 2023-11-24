<?php

namespace App\Models;

use App\Abstracts\AbstractModel;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReasonCloseAlert extends AbstractModel
{
    use HasFactory, HasUuid;

    protected $fillable = ['uuid', 'description'];
    protected $guarded = ['id'];
    public $timestamps = true;

    public function closedAlert()
    {
        return $this->hasMany(ClosedAlert::class);
    }

    public function stepAlerts()
    {
        return $this->hasMany(AlertStep::class);
    }
}
