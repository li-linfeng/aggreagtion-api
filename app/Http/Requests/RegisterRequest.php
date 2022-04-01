<?php

namespace App\Http\Requests;


class RegisterRequest extends FormRequest
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
            'nickname'              => 'required|string|max:255|unique:users',
            'contact' => [
                "required",
                "regex:/^(?:\+?86)?1(?:3\d{3}|5[^4\D]\d{2}|8\d{3}|7(?:[01356789]\d{2}|4(?:0\d|1[0-2]|9\d))|9[189]\d{2}|6[567]\d{2}|4[579]\d{2})\d{6}$/",
            ],
            'password'              => 'required|string|confirmed',
            'password_confirmation' => 'required|string',
            'code'                  => 'required|string',
        ];
    }
}
