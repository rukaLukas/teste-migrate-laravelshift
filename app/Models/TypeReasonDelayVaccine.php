<?php

namespace App\Models;

use App\Abstracts\AbstractModel;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TypeReasonDelayVaccine extends AbstractModel
{
    use HasFactory, HasUuid;
    
    protected $fillable = ['uuid', 'description'];

    protected $guarded = ['id'];

    public $timestamps = false;
    
    public function reasonDelayVaccines()
    {
        // return $this->hasMany(ReasonDelayVaccine::class);
        return $this->hasMany(ReasonDelayVaccine::class);
    }
}
