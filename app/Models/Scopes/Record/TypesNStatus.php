<?php
namespace App\Models\Scopes\Record;

use App\Abstracts\AbstractModel;
use App\Models\Alert;
use App\Models\Record;
use App\Models\StatusAlert;
use Illuminate\Support\Arr;

class TypesNStatus implements FilterRecord
{    
    private FilterRecord $next;

    public function filter($queryBuilder, array $params)
    {
        $types = [
            'alerts' => 'alerts',
            'events' => 'events',
            'all' => 'all'
        ];
        $status = [
                'pending' => 'pending',
                'ongoing' => 'ongoing',
                'finished' => 'finished',
                'all' => 'all'
            ];

        if (Arr::exists($params, 'type') && in_array($params['type'], $types)) {
            $nameFilter = "App\\Models\\Scopes\\Filters\\" . ucfirst($params["type"]) . "Filter";
            // $queryBuilder = parent::scopeQuery($queryBuilder, $params);
            $queryBuilder = Record::parent()::scopeQuery($queryBuilder, $params);
            $filter = app()->make($nameFilter, ['queryBuilder' => $queryBuilder]);

            $status = $params['status'] ?? 'all';
            $nameSpecializedFilter = "App\\Models\\Scopes\\Filters\\" . ucfirst($params["type"]) . ucfirst($status) . "Filter";
            $specializedFilter = app()->make($nameSpecializedFilter);

            $filter->setFilter($specializedFilter);
            return $queryBuilder = $filter->applyFilter($status);
        }   
        
        return $this->next->filter($queryBuilder, $params);
    }

    public function setNext(FilterRecord $next): void
    {
        $this->next = $next;
    }
}