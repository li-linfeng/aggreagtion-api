<?php

namespace App\Observers;

use App\Models\UserCollect;
use App\Models\UserPlayList;
use App\Models\UserPlayListResourceRel;

class UserCollectObserver
{
    public function created(UserCollect $collect)
    {
        $this->addResourceToUserCollectList($collect);
    }


    public function deleted(UserCollect $collect)
    {

        $user_play_list = UserPlayList::where([
            'user_id' => $collect->user_id,
            'type' => 'like',
        ])->first();

        UserPlayListResourceRel::where([
            'user_id' => $collect->user_id,
            'resource_id' => $collect->resource_id,
            'play_list_id' => $user_play_list->id
        ])->delete();
    }




    protected function addResourceToUserCollectList(UserCollect $collect)
    {
        $user_id = $collect->user_id;
        //为用户生成一个收藏播放列表
        $user_play_list = UserPlayList::firstOrCreate([
            'user_id' => $user_id,
            'type' => 'like',
            'name' => '我的最爱'
        ]);
        UserPlayListResourceRel::create([
            'user_id' => $user_id,
            'resource_id' => $collect->resource_id,
            'play_list_id' => $user_play_list->id
        ]);
    }
}
