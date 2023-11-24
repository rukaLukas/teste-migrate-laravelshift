<?php
namespace App\Http\Requests\Alert;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReasonsDelayVaccineRequest extends FormRequest
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
            'reasons_delay_vaccine' => 'required|array|min:1'
        ];
    }
}
