<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CaseStepCreateRequest extends FormRequest
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
            'alert_id' => 'required|integer',
            'user_id' => 'required|integer',
            'is_alert' => 'integer',
            'is_analysis' => 'integer',
            'is_forwarded' => 'integer',
            'is_vaccineroom' => 'integer',
            'is_done' => 'integer',
            'is_closed' => 'integer',
        ];
    }
}
