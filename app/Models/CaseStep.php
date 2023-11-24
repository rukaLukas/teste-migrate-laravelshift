<?php

namespace App\Models;

use App\Abstracts\AbstractModel;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CaseStep extends AbstractModel
{
    use HasFactory, HasUuid;

    protected $guarded = ['id'];
    protected $fillable = ['uuid'];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function alert()
    {
        return $this->belongsTo(Alert::class);
    }
}
