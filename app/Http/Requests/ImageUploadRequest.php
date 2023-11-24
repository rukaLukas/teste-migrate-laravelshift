<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class ImageUploadRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {        
        return [
            'image' => 'required|mimes:jpg,jpeg,png|max:5000',
        ];
    }

    public function messages()
    {
        return [
            'image.max' => 'O tamanho da imagem não pode ser superior a 5MB'            
        ];
    }
}