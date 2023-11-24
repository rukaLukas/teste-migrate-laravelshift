<?php

namespace App\Http\Requests;

use App\Models\Record;
use App\Models\Vaccine;
use App\Models\TargetPublic;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\TypeStatusVaccination;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;

class AlertStepRequest extends FormRequest
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
            'step' => 'required|uuid|exists:status_alerts,uuid',
        ];
    }    
}
