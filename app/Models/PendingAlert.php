<?php

namespace App\Models;

use App\Abstracts\AbstractModel;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PendingAlert extends AbstractModel
{
    use HasFactory, HasUuid;

    protected $fillable = ['uuid'];

    protected $guarded = ['id'];

    public $timestamps = false;

    public function getRelations()
    {
        return ['user', 'alert'];
    }

    public function alert()
    {
        return $this->belongsTo(Alert::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
