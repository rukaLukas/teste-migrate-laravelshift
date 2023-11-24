<?php

namespace App\Http\Resources;

use App\Http\Resources\GeolocationResourceInterface;

class GeolocationResource implements GeolocationResourceInterface
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'stateCode' => $request->stateCode,
            'state' => $request->state,
            'city' => $request->city,
            'district' => $request->district,
            'street' => $request->street,
            'postalCode' => $request->postalCode,
            'lat' => $request->lat,
            'lng' => $request->lng
        ];
    }
}
