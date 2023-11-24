<?php
namespace App\Rules;

use App\Models\Accession;
use Illuminate\Contracts\Validation\Rule;

class UniqueAccessionByCounty implements Rule
{
    public function passes($attribute, $value)
    {
        $accession = Accession::where('county_id', $value)
                     ->whereNull('deleted_at')
                     ->first();
        
        return is_null($accession);       
    }

    public function message()
    {
        return 'Já existe registro para esse município na BAV.';
    }
}