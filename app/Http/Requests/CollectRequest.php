<?php

namespace App\Http\Requests;

class CollectRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'resource_id' => 'required|exists:resources,id',
        ];
    }

    public  function attributes()
    {
        return [
            'resource_id' => '资源'
        ];
    }
}
