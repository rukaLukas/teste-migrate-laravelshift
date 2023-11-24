<?php

namespace App\Http\Requests;

use App\Helper\Number;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class GovernmentOfficeUserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {                
        return [
            'user_id' => 'required|uuid|exists:App\Models\User,uuid',
            'government_office_id' => 'required|uuid|exists:App\Models\GovernmentOffice,uuid',            
        ];
    }
}
