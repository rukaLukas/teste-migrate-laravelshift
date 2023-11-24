<?php

namespace App\Models;

use App\Abstracts\AbstractModel;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MenuCounty extends AbstractModel
{
    use HasFactory, HasUuid;

    protected $fillable = ['uuid', 'name', 'menu_id', 'county_id'];
    protected $guarded = ['id'];
    public $timestamps = true;


    public function county()
    {
        return $this->belongsTo(County::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

}
