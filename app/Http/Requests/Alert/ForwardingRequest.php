<?php
namespace App\Http\Requests\Alert;

use Illuminate\Foundation\Http\FormRequest;

class ForwardingRequest extends FormRequest
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
            'record_id' => 'required|uuid|exists:records,uuid',
            'user_id' => 'required|uuid',
            'description' => 'required|min:8',
            'government_office_id' => 'required|uuid|exists:government_offices,uuid',
        ];
    }
}
