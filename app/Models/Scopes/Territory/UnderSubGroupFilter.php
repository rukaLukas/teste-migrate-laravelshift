<?php
namespace App\Models\Scopes\Territory;

use App\Models\Scopes\Territory\County;
use App\Models\Scopes\Territory\Region;
use App\Models\Scopes\Territory\SubRegion;
use App\Models\Scopes\Territory\VaccineRoom;

class UnderSubGroupFilter
{
    public function filter($params)
    {
        $filterByVaccineRoom = new VaccineRoom();
        $filterBySubRegion = new SubRegion();  
        $filterByRegion = new Region();
        $filterByCounty = new County($params['county_id']); 

        $filterByVaccineRoom->setNext($filterBySubRegion);
        $filterBySubRegion->setNext($filterByRegion);
        $filterByRegion->setNext($filterByCounty);
        
        return $filterByVaccineRoom->filter();  
    }
}