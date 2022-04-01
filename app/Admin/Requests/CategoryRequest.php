<?php

namespace App\Admin\Requests;

class CategoryRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'                => 'required|string|unique:categories,name',
        ];
    }

    public  function attributes()
    {
        return [
            'name'                => '名称',
        ];
    }
}
