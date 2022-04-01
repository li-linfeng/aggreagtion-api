<?php

namespace  App\Services;

use App\Models\UserSocialAccount;

class UserSocialAccountService
{

    public function  createOrUpdateRecord($social, $params)
    {
        $data = [
            'uid'     => $params['id'],
            'social'  => $social
        ];

        return  UserSocialAccount::updateOrCreate($data, [
            'nickname' => $params['nickname'],
            'avatar'   => $params['avatar'],
            'name'     => $params['name'],
            'email'    => $params['email'],
        ]);
    }
}
