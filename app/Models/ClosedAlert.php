<?php

namespace App\Models;

use App\Abstracts\AbstractModel;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClosedAlert extends AbstractModel
{
    use HasFactory, HasUuid;

    protected $fillable = ['uuid', 'user_id', 'alert_id', 'reason_close_alert_id', 'description'];
    protected $guarded = ['id'];
    public $timestamps = true;

    public function alert()
    {
        return $this->belongsTo(Alert::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reasonCloseAlert()
    {
        return $this->belongsTo(ReasonCloseAlert::class);
    }
}
