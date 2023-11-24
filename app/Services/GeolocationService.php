<?php
namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Dto\AddressDto;
use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\Http;
use App\Interfaces\Service\GeolocationServiceInterface;

class GeolocationService implements GeolocationServiceInterface
{
    public function getAddressByPostalCode(string $postalCode): AddressDto
    {
        $response = Http::acceptJson()->get(env("HERE_API") . "/geocode?qq=postalCode=${postalCode}&apiKey=" . env("HERE_API_KEY"));
        $objRes = json_decode($response->body());
        
        throw_if(
            count($objRes->items) == 0,
            new GeneralException(array("Endereco não encontrado, ou cep inválido"))
        );
        
        return new AddressDto($objRes->items[0]->address->stateCode, 
                            $objRes->items[0]->address->state,
                            $objRes->items[0]->address->city,
                            '',
                            '',
                            $objRes->items[0]->address->postalCode,
                            $objRes->items[0]->position->lat,
                            $objRes->items[0]->position->lng);
    }

    /**
     * getAddressByLatLng function
     *
     * @param float $lat
     * @param float $lng
     * @return array
     */
    public function getAddressByLatLng(float $lat, float $lng): AddressDto
    {
        $response = Http::acceptJson()->get(env("HERE_API") . "/revgeocode?at=${lat}%2C${lng}&lang=en-US&apiKey=" . env("HERE_API_KEY"));
        $objRes = json_decode($response->body());

        throw_if(
            count($objRes->items) == 0,
            new GeneralException(array("Endereco não encontrado, ou georeferenciamento inválido"))
        );

        return new AddressDto($objRes->items[0]->address->stateCode, 
                            $objRes->items[0]->address->state,
                            $objRes->items[0]->address->city,
                            $objRes->items[0]->address->district,
                            $objRes->items[0]->address->street,
                            $objRes->items[0]->address->postalCode,
                            $objRes->items[0]->position->lat,
                            $objRes->items[0]->position->lng);
    }
}
