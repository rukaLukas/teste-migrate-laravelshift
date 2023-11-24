<?php
namespace App\Models\Scopes\Record;

class RecordFilter
{
    public function filter($queryBuilder, $params)
    {
        $filterByTypeNStatus = new TypesNStatus();          
        $filterByPendingAlert = new PendindgAlert();
        $filterSearchAll = new SearchAll();
        
        $filterByTypeNStatus->setNext($filterByPendingAlert);
        $filterByPendingAlert->setNext($filterSearchAll);       
        
        return $filterByTypeNStatus->filter($queryBuilder, $params);  
    }
}