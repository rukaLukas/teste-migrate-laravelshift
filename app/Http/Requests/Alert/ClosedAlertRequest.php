<?php
namespace App\Http\Requests\Alert;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ClosedAlertRequest extends FormRequest
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
        return $this->validate();
    }

    private function validate() {
        
        $validator = Validator::make(Request::all(), [
            'alert_id' => 'required|uuid',
            'user_id' => 'required|uuid',            
            'reason_close_alert_id' => 'required',
        ]);

        $validator->sometimes('description', 'required|min:8', function ($input) {
            return $input->reason_close_alert_id == 4;
        });

        return $validator->getRules();
    }
}
