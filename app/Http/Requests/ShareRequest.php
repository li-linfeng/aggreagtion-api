<?php

namespace App\Http\Requests;

class ShareRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 'channel' => 'required',
        ];
    }

    public  function attributes()
    {
        return [
            'channel' => '分享渠道'
        ];
    }
}
