<?php
namespace App\Dto;

class AddressDto
{ 
    /**
     *
     * @var string
     */
    protected string $stateCode;
    
    /**
     *
     * @var string
     */
    protected string $state;
    
    /**
     *
     * @var string
     */
    protected string $city;
    
    /**
     *
     * @var string
     */
    protected string $district;
    
    /**
     *
     * @var string
     */
    protected string $street;
    
    /**
     *
     * @var string
     */
    protected string $postalCode;
    
    /**
     * latitude
     *
     * @var float
     */
    protected float $lat;
    
    /**
     * longitude
     *
     * @var float
     */
    protected float $lng;

    /**
     * construct function
     *
     * @param string $stateCode
     * @param string $state
     * @param string $city
     * @param string $district
     * @param string $street
     * @param string $postalCode
     * @param float $lat
     * @param float $lng
     */
    public function __construct(string $stateCode, 
                                string $state,
                                string $city,
                                string $district,
                                string $street,
                                string $postalCode,
                                float $lat,
                                float $lng)
    {
        $this->stateCode = $stateCode;
        $this->state = $state;
        $this->city = $city;
        $this->district = $district;
        $this->street = $street;
        $this->postalCode = $postalCode;
        $this->lat = $lat;
        $this->lng = $lng;
    }

    function __get($name)
    {
        if(property_exists($this,$name)){
          return $this->$name;
        }
        return null;
    }
}
