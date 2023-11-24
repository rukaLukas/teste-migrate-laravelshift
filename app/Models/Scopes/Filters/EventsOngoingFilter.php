<?php 
namespace App\Models\Scopes\Filters;

use App\Models\StatusAlert;
use Illuminate\Database\Eloquent\Builder;

class EventsOngoingFilter implements IFilter
{  
    public function filter(Builder $queryBuilder): Builder
    {                        
        $queryBuilder->whereHas('alertSteps', function($q) {
            $q->whereIn('status_alert_id', [
                StatusAlert::ANALISE_TECNICA,
                StatusAlert::ENCAMINHAMENTO,
                StatusAlert::SALA_VACINA
            ]);
        });

        return $queryBuilder;
    }
}