<?php
namespace App\Models\Scopes\Record;

use App\Models\Alert;
use App\Models\Record;
use App\Models\StatusAlert;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder;

class PendindgAlert implements FilterRecord
{    
    private FilterRecord $next;

    public function filter(Builder $queryBuilder, array $params)
    {
        if (!Arr::exists($params, 'type') && Arr::exists($params, 'status')) {           
            if ($params['status'] === Alert::STATUS['PENDING']) {
                $statusAlertsFilter = StatusAlert::where('name', '!=', StatusAlert::STATUS[StatusAlert::ALERTA])->pluck('id')->toArray();
                
                return Record::query()->orWhereDoesntHave('alertSteps', function ($query) use ($statusAlertsFilter) {
                    $query->whereIn('status_alert_id', $statusAlertsFilter);
                });
            }
        }
        
        return $this->next->filter($queryBuilder, $params);
    }

    public function setNext(FilterRecord $next): void
    {
        $this->next = $next;
    }
}