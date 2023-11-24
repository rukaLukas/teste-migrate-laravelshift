<?php

namespace App\Http\Resources;

use App\Models\Accession;
use Carbon\Carbon;
use App\Models\Pronoun;
use App\Http\Resources\Configuration\GovernmentOfficeResource;
use App\Models\Occupation;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Configuration\GroupResource;
use App\Models\GovernmentOffice;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->uuid ?? $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'two_factor' => $this->two_factor_confirmed_at ? true : false,
            'avatar' => $this->avatar,
            'cpf' => $this->cpf ?? '',
            'birthdate' => $this->birthdate,
            'pronoun' => new PronounResource(Pronoun::find($this->pronoun_id)) ?? '',
            'cell_phone' => $this->cell_phone ?? '',
            'office_phone' => $this->office_phone ?? '',
            'group' => $this->group ?? '',
            'subGroup' => $this->subGroup ?? '',
            'underSubGroup' => $this->underSubGroup ?? '',
            'occupation' => new OccupationResource(Occupation::find($this->occupation_id)) ?? '',
            'photo_url' => $this->photo_url,
            'name_menu' => $this->name_menu,
            'accession_id' => $this->accession->uuid ?? null,
            'county_id' => $this->county_id,
            'county' => new CountyResource($this->county),
            'birthdate_formatted' => $this->birthdate_formatted,
//            'permissions' => Occupation::PERMISSIONS[$this->occupation_id] ?? [],
            'cpf_formatted' => $this->cpf_formatted,
            'cell_phone_formatted' => $this->cell_phone_formatted,
            'office_phone_formatted' => $this->office_phone_formatted,
            'accession_by_county' => new AccessionByCountyResource($this->accessionByCounty) ?? '',
            'government_offices' => GovernmentOfficeResource::collection($this->county->governmentOffices) ?? '',
            'has_password' => $this->password ? true : false,
            'menu' => MenuByCountyResource::collection($this->menuByCounty)
        ];
    }
}
