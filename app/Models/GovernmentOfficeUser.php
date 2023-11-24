<?php

namespace App\Models;

use App\Abstracts\AbstractModel;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GovernmentOfficeUser extends AbstractModel
{
    use HasFactory, HasUuid;

    protected $fillable = ['uuid'];
    protected $guarded = ['id'];
    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function governmentOffice()
    // {
    //     return $this->belongsTo(GovernmentOffice::class);
    // }
}
