<?php

namespace  App\Services;



use App\Models\User;
use App\Models\UserLoginLogLog;
use Carbon\Carbon;

class UserLoginService
{
    public function  createLoginLog(User  $user)
    {
        UserLoginLogLog::create([
            'user_id' => $user->id,
            'ip'      => app('request')->ip(),
            'imei'    => '',
            'client'  => '',
            'imei'    => '',
        ]);
        //更新最新一次登录时间
        $user->update(['latest_login_time' => Carbon::now()->toDateTimeString()]);
        return;
    }
}
