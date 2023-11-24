<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = ['uuid', 'name'];

    protected $guarded = ['id', 'uuid'];

    public $timestamps = false;
}
