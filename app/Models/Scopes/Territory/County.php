<?php
namespace App\Models\Scopes\Territory;

use App\Models\Group;
use Illuminate\Support\Arr;
use App\Models\UnderSubGroup;
use Illuminate\Database\Eloquent\Builder;

class County implements FilterTerritory
{
    private FilterTerritory $next;
    private int $countyId;

    public function __construct(int $countyId)
    {
        $this->countyId = $countyId;
    }

    public function filter()
    {
        $groups = Group::where('county_id', '=', $this->countyId)->get();
        foreach ($groups as $group) $whereFilter[] = $group->subGroups->pluck('id');
        $whereFilter = Arr::flatten($whereFilter); 
      
        return UnderSubGroup::whereIn('sub_group_id', $whereFilter);                
    }

    public function setNext(FilterTerritory $next): void
    {
        //
    }
}