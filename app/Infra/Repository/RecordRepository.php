<?php

namespace App\Infra\Repository;

use App\Models\Alert;
use App\Models\Record;
use App\Models\AlertStep;
use App\Models\StatusAlert;
use App\Models\TargetPublic;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Abstracts\AbstractRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class RecordRepository extends AbstractRepository
{

    /**
     * @var Model
     */
    protected $model;

    public function __construct(Record $model)
    {
        $this->model = $model;
    }

    public function getPaginationList(array $params, array $with = [], int $perPage = null)
    {
        $countyId = Auth::user()->county_id;
        $underSubGroupFilter = app()->make('App\Models\Scopes\Territory\UnderSubGroupFilter');      
        return $this
            ->getModel()
            ->with([
                'alertSteps',
                'comments',
                'lastAlert',
                'alerts' => function($query) use ($countyId, $params) {
                    return $query->query($params)
                        ->where('county_id', '=', $countyId);                    
                },              
            ])
            ->query($params)
            ->whereHas('alerts', function($query) use ($countyId, $params, $underSubGroupFilter) {
                $params['county_id'] = $countyId;               
                foreach ($underSubGroupFilter->filter($params)->get() as $vaccineRoom) $idsVaccineRoom[] = $vaccineRoom->id;                                
                    return $query->query($params)
                        ->whereIn('vaccine_room_id', $idsVaccineRoom);                                  
            })            
            ->paginate()
            ->withQueryString();        
    }


    public function formatParams(array $params): array
    {
        // TODO: Implement formatParams() method.
    }
}
