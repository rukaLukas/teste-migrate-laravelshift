<?php

namespace App\Models;

use App\Abstracts\AbstractModel;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GovernmentOffice extends AbstractModel
{
    use HasFactory, HasUuid;

    protected $fillable = ['uuid', 'name', 'email', 'county_id', 'type'];
    protected $guarded = ['id'];
    public $timestamps = true;

    const SECRETARIA_EDUCACAO = 1;
    const SECRETARIA_ASSISTENCIA_SOCIAL = 2;

    const DEFAULT_TYPES = [
        self::SECRETARIA_EDUCACAO => 'SECRETARIA_EDUCACAO',
        self::SECRETARIA_ASSISTENCIA_SOCIAL => 'SECRETARIA_ASSISTENCIA_SOCIAL',        
    ];

    const DEFAULT = [        
         [
            'name' => 'Secretaria Municipal de Educação',
            'email' => '',
            'type' => '1'
        ],
        [          
            'name' => 'Secretaria Municipal de Assistência Social',
            'email' => '',
            'type' => '2'
        ]        
    ];

    public function reasonDelayVaccines()
    {        
        return $this->belongsToMany(ReasonDelayVaccine::class, 'go_rdv')
            ->withPivot('id')
            ->as('go_rdv');            
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function county()
    {
        return $this->belongsTo(County::class);
    }
}
