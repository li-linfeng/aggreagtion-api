<?php

namespace App\Http\Controllers;

use App\Http\Requests\CollectRequest;
use App\Models\UserCollect;

class UserCollectController extends Controller
{

    public function store(CollectRequest $request)
    {
        $user_id = auth('api')->id();
        UserCollect::firstOrCreate([
            'user_id'       => $user_id,
            'resource_id'   => $request->resource_id,
            'collection_id' => $request->collection_id
        ]);
        return $this->response()->array(['message' => '提交成功']);
    }
}
