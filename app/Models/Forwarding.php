<?php

namespace App\Models;

use App\Abstracts\AbstractModel;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Forwarding extends AbstractModel
{
    use HasFactory, HasUuid;

    protected $fillable = ['uuid'];
    protected $guarded = ['id'];
    public $timestamps = true;

    // public function alert() 
    // {
    //     return $this->belongsTo(Alert::class);
    // }

    public function record() 
    {
        return $this->belongsTo(Record::class);
    }

    public function user() 
    {
        return $this->belongsTo(User::class);
    }
}
