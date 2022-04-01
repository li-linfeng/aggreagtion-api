<?php

namespace  App\Services;

use App\Models\ShareRecord;
use Carbon\Carbon;

class ShareService
{
    const USER_SHARE_LIMIT_DAILY = 10;

    public function share($channel)
    {
        $user_id = auth('api')->id();
        //判断是否达到当日分享上限
        if (!$this->isUpToShareLimit($user_id)) {
            //给分享用户增加一天无限卡次数
            app(UserMuseAccountService::class)->share($user_id);
            //记录redis日志
            $limit = $this->addRedisRecord($user_id);
            $left = self::USER_SHARE_LIMIT_DAILY - $limit;
        };
        //增加分享记录
        $this->addShareRecord($channel, $user_id);
        return  $left;
    }



    protected function addShareRecord($channel, $user_id)
    {
        $data = [
            'channel' => $channel,
            'user_id' => $user_id
        ];
        return ShareRecord::create($data);
    }

    /**
     * 增加redis记录,设置过期时间
     */
    protected function addRedisRecord($user_id)
    {
        $redis = app('redis');
        //第一次插入redis时设置过期时间，第二天0点重置
        if ($redis->exists("share:{$user_id}")) {
            $limit =  $redis->incr("share:{$user_id}");
        } else {
            $time = Carbon::now()->diffInSeconds(date("Y-m-d 23:59:59"));
            $limit = 1;
            $redis->setex("share:{$user_id}", $time, $limit);
        }
        return $limit;
    }

    /**
     * 判断是否达到当日上限
     */
    protected function isUpToShareLimit($user_id)
    {
        $redis = app('redis');
        $limit = $redis->get("share:{$user_id}");
        return $limit >= SELF::USER_SHARE_LIMIT_DAILY;
    }
}
