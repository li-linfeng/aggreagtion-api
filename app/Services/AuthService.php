<?php

namespace  App\Services;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Traits\TokenTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    use TokenTrait;

    public function register(RegisterRequest $registerRequest)
    {
        //校验验证码是否正确
        $codeServiced =  app(CodeService::class)->setData([
            'driver'  => $registerRequest->input('driver', 'mobile'),
            'type'    => $registerRequest->input('type', 'register'),
            'contact' => $registerRequest->contact,
        ]);
        $codeServiced->checkCode($registerRequest->code);

        //判断邀请码是否存在
        $share_code = $registerRequest->share_code;
        $parent_id = 0;
        if ($share_code) {
            $parent_id = $this->checkShareCode($share_code);
            //给父级用户增加时间
            app(UserMuseAccountService::class)->inviteUser($parent_id);
        }
        //创建用户
        $user = app(UserService::class)->findOrCreateUserByMobile([
            'nickname'  => $registerRequest->nickname,
            'mobile'    => $registerRequest->contact,
            'password'  => bcrypt($registerRequest->password),
            'reg_type'  => 'mobile',
            'reg_time'  => Carbon::now()->toDateTimeString(),
            'parent_id' => $parent_id
        ]);

        return $user;
    }



    public function login()
    {
        $user = $this->validateUser();
        //第一次登陆，获得三天免费卡
        if (!$user->first_login_time) {
            app(UserMuseAccountService::class)->firstLogin($user->id);
            $user->first_login_time = Carbon::now()->toDateTimeString();
            $user->save();
        }
        //记录登录日志
        app(UserLoginService::class)->createLoginLog($user);

        return  $this->generateAccessTokenForUser($user);
    }


    public function checkShareCode($share_code)
    {
        $user = User::where('share_code', $share_code)->first();
        if (!$user) {
            abort('404', '邀请码不存在');
        }
        return $user->id;
    }

    protected function validateUser()
    {
        $contact = request()->input('contact');
        $password = request()->input('password');
        $user = User::where('mobile', $contact)->first();
        if (!$user) {
            abort(422, "手机号不存在");
        }
        if (!Hash::check($password, $user->password)) {
            abort(422, "密码错误");
        }
        return $user;
    }
}
