<?php
namespace App\Interfaces\Service;

use App\Dto\AddressDto;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Model;
use App\Interfaces\Repository\RepositoryInterface;

interface GeolocationServiceInterface
{
    /**
     * getAddressByPostalCode function
     *
     * @param string $postalCode
     * @return array
     */
    public function getAddressByPostalCode(string $postalCode): AddressDto;

    /**
     * getAddressByLatLng function
     *
     * @param float $lat
     * @param float $lng
     * @return array
     */
    public function getAddressByLatLng(float $lat, float $lng): AddressDto;
}
