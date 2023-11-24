<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GovernmentAgency extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = ['uuid'];
    protected $guarded = ['id'];
}
