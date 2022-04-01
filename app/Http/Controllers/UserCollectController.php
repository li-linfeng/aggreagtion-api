<?php

namespace App\Http\Controllers;

use App\Http\Requests\CollectRequest;
use App\Models\UserCollect;
use App\Models\UserPlayList;
use App\Models\UserPlayListResourceRel;
use Illuminate\Http\Request;

class UserCollectController extends Controller
{

    public function store(CollectRequest $request)
    {
        $user_id = auth('api')->id();
        UserCollect::firstOrCreate([
            'user_id' => $user_id,
            'resource_id' => $request->resource_id
        ]);
        return $this->response()->noContent();
    }

    public function disCollect(Request $request)
    {
        $user_id = auth('api')->id();
        UserCollect::where([
            'user_id' => $user_id,
            'resource_id' => $request->resource_id
        ])->delete();
        $user_play_list = UserPlayList::where([
            'user_id' => $user_id,
            'type' => 'like',
        ])->first();

        UserPlayListResourceRel::where([
            'user_id' => $user_id,
            'resource_id' => $request->resource_id,
            'play_list_id' => $user_play_list->id
        ])->delete();

        return $this->response()->noContent();
    }
}
