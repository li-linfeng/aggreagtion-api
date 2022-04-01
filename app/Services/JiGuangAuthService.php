<?php

namespace  App\Services;

use App\Traits\TokenTrait;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class JiGuangAuthService
{
    use TokenTrait;

    public function  login()
    {
        $mobile = $this->getMobileFromToken(request()->login_token);

        $user = app(UserService::class)->findOrCreateUserForMobileAccount($mobile);

        /**
         *  第一次登陆，获得三天免费卡,更新第一次登陆时间
         */
        if (!$user->first_login_time) {
            app(UserMuseAccountService::class)->firstLogin($user->id);
            $user->update(['first_login_time' => Carbon::now()->toDateTimeString()]);
        }
        /**
         *  更新最后一次登陆时间
         */
        $user->update(['latest_login_time' => Carbon::now()->toDateTimeString()]);

        /**
         * 如果是第三方登录，则只取第一次登录的uid作为密码
         *
         */
        $result = $this->generateAccessTokenForUser($user);

        /**
         * 记录登录日志
         */
        app(UserLoginService::class)->createLoginLog($user);

        return $result;
    }


    protected function getMobileFromToken($login_token)
    {
        try {
            $client = new Client();
            $response = $client->request('POST', 'https://api.verification.jpush.cn/v1/web/loginTokenVerify', [
                'headers' => [
                    'Authorization' => "Basic " . $this->preSignToken()
                ],
                'json' => [
                    'loginToken' => $login_token
                ]
            ]);
            $result = (array) json_decode($response->getBody()->getContents(), true);
            if ($result['code'] != 8000) {
                abort(422, $result['content']);
            }
            $phone = $this->parseMobile($result['phone']);
            return $phone;
        } catch (GuzzleException $exception) {
            app('log')->error('极光认证错误-----' . $exception->getMessage());
            abort(500, "认证错误请重试");
        }
    }


    protected function preSignToken()
    {
        $string = config('jiguang.app_key') . ":" . config('jiguang.app_secret');
        return  base64_encode($string);
    }


    protected function parseMobile($mobileString)
    {
        //私钥地址
        $prikey = config_path() . "/cert/pri.key";
        try {
            $r = openssl_private_decrypt(base64_decode($mobileString), $result, openssl_pkey_get_private(file_get_contents($prikey)));
        } catch (Exception $exception) {
            app('info')->log('解析错误----' . json_encode([
                'mobile' => $mobileString
            ]));
            abort(500, '解析错误--' . $exception->getMessage());
        }

        return $result;
    }
}
