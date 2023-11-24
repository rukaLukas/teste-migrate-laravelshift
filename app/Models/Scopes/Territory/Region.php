<?php
namespace App\Models\Scopes\Territory;

use App\Models\GroupUser;
use App\Models\UnderSubGroup;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder;

class Region implements FilterTerritory
{
    private FilterTerritory $next;

    public function filter()
    {
        $groupUsers = GroupUser::where('user_id', '=', auth()->user()->id)->get();
        if (count($groupUsers) > 0) {
            foreach ($groupUsers as $groupUser) $whereFilter[] = $groupUser->group->subGroups->pluck('id');
            $whereFilter = Arr::flatten($whereFilter);             
            
            return UnderSubGroup::whereIn('sub_group_id', $whereFilter);        
        }
        
        return $this->next->filter();
    }

    public function setNext(FilterTerritory $next): void
    {
        $this->next = $next;
    }
}