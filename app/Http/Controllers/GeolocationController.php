<?php

namespace App\Http\Controllers;

use App\Abstracts\AbstractController;
use TypeError;
use App\Services\GeolocationService;
use App\Http\Resources\GeolocationResource;
use App\Exceptions\GeneralValidationException;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\GeolocationResourceInterface;
use App\Interfaces\Service\GeolocationServiceInterface;

class GeolocationController extends AbstractController
{
    // protected $resource = GeolocationResource::class;

    /**
     * @var GeolocationServiceInterface
     */
    protected $service;

    /**
     *
     * @var GeolocationResourceInterface
     */
    protected $resource;

    public function __construct(GeolocationService $service, GeolocationResource $resource)
    {
        $this->service = $service;
        $this->resource = $resource;
    }

    public function addressByPostalCode(string $postalCode)
    {        
        try {
            $address = $this->service->getAddressByPostalCode($postalCode);            
            return $this->ok($this->resource->toArray($address));
        } catch (\Exception | ValidationException | GeneralValidationException $e) {
            return $this->handleException($e);
        }
    }

    public function addressByLatLng(string $latLng)
    {
        try {
            $arrLatLng = explode(',', $latLng);
            $lat = $arrLatLng[0];
            $lng = $arrLatLng[1];
            $address = $this->service->getAddressByLatLng($lat, $lng);
            
            return $this->ok($this->resource->toArray($address));
        } catch (\Exception | GeneralValidationException | TypeError $e) {
            if ($e instanceof GeneralValidationException) {
                return $this->error($this->messageErrorDefault, $e->getErrors());
            }
            if ($e instanceof TypeError) {
                return $this->error($this->messageErrorDefault, array("latitude e longitude devem ser do tipo float"));
            }
        }

    }
}
