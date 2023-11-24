<?php 
namespace App\Models\Scopes\Filters;

use Illuminate\Database\Eloquent\Builder;

class EventsFilter extends RecordFilter
{
    protected $queryBuilder;

    public function __construct(Builder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    public function setFilter(IFilter $filter)
    {
        return $this->filter = $filter;
    }

    public function applyFilter()
    {
        return $this->filter->filter($this->queryBuilder);
    }
}