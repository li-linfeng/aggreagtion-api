<?php

namespace App\Admin\Requests;

class ResourceRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'        => 'required|string|unique:resources,name',
            'link'        => 'required|string|unique:resources,link',
            'description' => 'string',
            'category_id' => 'required|integer|exists:categories,id',
        ];
    }

    public  function attributes()
    {
        return [
            'name'        => '名称',
            'link'        => '资源地址',
            'description' => '描述',
            'category_id' => '所属分类',
        ];
    }
}
