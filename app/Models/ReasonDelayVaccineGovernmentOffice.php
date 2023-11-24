<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;


class ReasonDelayVaccineGovernmentOffice extends Pivot
{
    protected $table = 'go_rdv';  

    protected $fillable = [
        'id'
    ];
    
    public $incrementing = true;    

    public $timestamps = false;
}
