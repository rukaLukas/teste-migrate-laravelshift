<?php
namespace App\Models\Scopes\Territory;

use App\Models\SubGroupUser;
use App\Models\UnderSubGroup;
use Illuminate\Database\Eloquent\Builder;

class SubRegion implements FilterTerritory
{
    private FilterTerritory $next;

    public function filter()
    {
        $subGroupUsers = SubGroupUser::where('user_id', '=', auth()->user()->id)->get();
        if (count($subGroupUsers) > 0) return UnderSubGroup::whereIn('sub_group_id', $subGroupUsers->pluck('sub_group_id'));
        
        return $this->next->filter();
    }

    public function setNext(FilterTerritory $next): void
    {
        $this->next = $next;
    }
}