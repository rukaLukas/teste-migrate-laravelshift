<?php

namespace App\Models;

use App\Abstracts\AbstractModel;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VaccineRoom extends AbstractModel
{
    use HasFactory, HasUuid;

    protected $fillable = ['uuid'];
    protected $guarded = ['id'];

    protected $table = 'under_sub_groups';

    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }
}
