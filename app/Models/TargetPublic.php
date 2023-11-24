<?php

namespace App\Models;

use App\Abstracts\AbstractModel;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TargetPublic extends AbstractModel
{
    use HasFactory, HasUuid;

    protected $table = 'target_publics';

    protected $fillable = ['uuid', 'name'];

    protected $guarded = ['id'];

    public function alerts()
    {
        return $this->hasMany(Record::class);
    }

    public function reasonDelayVaccines()
    {
        return $this->belongsToMany(ReasonDelayVaccine::class)->using(ReasonDelayVaccineTargetPublic::class);
    }    

    // public function reasonDelayVaccine()
    // {
    //     return $this->hasMany(ReasonDelayVaccine::class);
    // }
}
