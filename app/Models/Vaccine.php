<?php

namespace App\Models;

use App\Abstracts\AbstractModel;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vaccine extends AbstractModel
{
    use HasFactory, HasUuid;

    protected $fillable = ['uuid', 'name', 'schema', 'dose', 'aplication_age_month', 'limit_age_year', 'target_public_id', 'days_interval'];
    protected $guarded = ['id'];
    public $timestamps = true;

    public function targetPublic()
    {
        return $this->hasOne(TargetPublic::class, 'id', 'target_public_id');
    }

    public function notAppliedVaccines()
    {
        return $this->hasMany(NotAppliedVaccine::class);
    }

    public function delayedVaccines()
    {
        return $this->hasMany(DelayedVaccine::class);
    }
}
