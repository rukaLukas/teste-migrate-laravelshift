<?php

namespace App\Models;

use App\Abstracts\AbstractModel;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReasonNotAppliedVaccine extends AbstractModel
{
    use HasFactory, HasUuid;

    protected $fillable = ['uuid', 'description'];
    protected $guarded = ['id'];
    public $timestamps = false;
    
    public function stepAlerts()
    {
        return $this->hasMany(AlertStep::class);
    }
}
