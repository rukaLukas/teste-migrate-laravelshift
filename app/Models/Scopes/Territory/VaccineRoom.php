<?php
namespace App\Models\Scopes\Territory;

use App\Models\UnderSubGroup;
use App\Models\UnderSubGroupUser;
use Illuminate\Database\Eloquent\Builder;

class VaccineRoom implements FilterTerritory
{
    private FilterTerritory $next;

    public function filter()
    {        
        $underSubGroupUsers = UnderSubGroupUser::where('user_id', '=', auth()->user()->id)->get();
        if (count($underSubGroupUsers) > 0) return UnderSubGroup::whereIn('id', $underSubGroupUsers->pluck('under_sub_group_id'));
        
        return $this->next->filter();
    }

    public function setNext(FilterTerritory $next): void
    {
        $this->next = $next;
    }
}