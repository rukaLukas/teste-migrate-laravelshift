<?php 
namespace App\Models\Scopes\Filters;

abstract class RecordFilter
{
    protected $filter;

    abstract function setFilter(IFilter $filter);
    
    abstract function applyFilter();    
}