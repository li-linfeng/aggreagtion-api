<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

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
            'name'        => [
                'required', 'string',
                Rule::unique('resources', 'name')->where(function ($query) {
                    return $query->where('user_id', auth('api')->id());
                })
            ],
            'link'        => 'required|string',
            'description' => 'string',
            'collection_id' => 'required|integer|exists:collections,id',
        ];
    }

    public  function attributes()
    {
        return [
            'name'        => '资源',
            'link'        => '资源地址',
            'description' => '描述',
            'category_id' => '所属分类',
        ];
    }
}
