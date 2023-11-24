<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;


class ReasonDelayVaccineTargetPublic extends Pivot
{
    protected $table = 'tp_reason_delay_vaccine';  
    
    public $incrementing = true;    

    public $timestamps = false;
}
