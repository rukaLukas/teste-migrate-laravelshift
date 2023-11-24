<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Abstracts\AbstractModel;
use App\Models\ReasonDelayVaccine;
use App\Models\Scopes\RecordAlertScope;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CommentRecord extends AbstractModel
{
    use HasFactory, HasUuid;

    protected $guarded = ['id'];

    protected $fillable = [
        'uuid'
        ,'record_id'
        ,'user_id'
        ,'comment'    
    ];    

    public $timestamps = true;

    public function record()
    {
        return $this->belongsTo(Record::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
