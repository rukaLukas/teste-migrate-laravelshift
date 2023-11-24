<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Abstracts\AbstractModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Breed extends AbstractModel
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'uuid',
        'name'
    ];

    protected $guarded = ['id'];

    public $timestamps = true;

    public function alerts() 
    {
        return $this->hasMany(Record::class);
    }
}
