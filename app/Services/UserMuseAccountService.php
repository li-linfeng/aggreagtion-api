<?php

namespace  App\Services;


use App\Models\UserMuseAccount;
use App\Models\UserMuseAccountLog;
use Carbon\Carbon;

class UserMuseAccountService
{
    public  function  firstLogin($user_id)
    {
        return $this->addTimeToAccount($user_id, 'first_login', 3);
    }


    public  function  inviteUser($user_id)
    {
        return $this->addTimeToAccount($user_id, 'invite', 1);
    }

    public  function  share($user_id)
    {
        return $this->addTimeToAccount($user_id, 'share', 1);
    }

    public function  purchase($user_id, $days, $admin_id = 0)
    {
        return $this->addTimeToAccount($user_id, 'purchase',  $days, $admin_id);
    }


    public function addTimeToAccount($user_id, $type, $days, $admin_id = 0)
    {
        //不存在账户则创建
        $account = UserMuseAccount::firstOrCreate(['user_id' => $user_id]);
        if ($account->is_expired) { //账户时长已过期
            $now =  Carbon::now()->toDateTimeString();
            //将当前时间设为开始时间，并增加时长
            $account->update([
                'start_time' => $now,
                'end_time'   => Carbon::now()->addDays($days)->toDateTimeString(),
            ]);
        } else { //如果没有过期,则在原来基础上累加
            $account->update([
                'end_time'   => Carbon::parse($account->end_time)->addDays($days)->toDateTimeString(),
            ]);
        }
        //增加一条操作日志
        $params = [
            'user_id'    => $user_id,
            'account_id' => $account->id,
            'type'       => $type,
            'add_time'   => $days,
        ];

        if ($admin_id) {
            $params['admin_id'] = $admin_id;
        }

        $this->addLog($params);
    }


    protected  function  addLog($params)
    {
        return UserMuseAccountLog::create($params);
    }
}
