<?php

namespace App\Models;

use App\Abstracts\AbstractModel;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pronoun extends AbstractModel
{
    use HasFactory, HasUuid;

    protected $fillable = ['uuid', 'name'];
    protected $guarded = ['id'];
    public $timestamps = true;
}
