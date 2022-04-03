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
            'resource_id'   => 'required|exists:resources,id',
            'collection_id' => 'required|exists:collections,id',
        ];
    }

    public  function attributes()
    {
        return [
            'resource_id'   => '资源',
            'collection_id' => '收藏夹'
        ];
    }
}
