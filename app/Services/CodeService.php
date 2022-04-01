<?php

namespace App\Services;

use App\Models\Code;
use Carbon\Carbon;

class CodeService
{
    protected $mobile;

    protected $driver;

    protected $template;

    protected $code;


    public function setData($params)
    {
        $this->driver  = $params['driver']  ?? 'mobile';
        $this->type    = $params['type'] ?? '';
        $this->contact = $params['contact'] ?? '';
        return $this;
    }

    //生成驗證碼
    public function generateCodes()
    {
        //校驗當日發送上限
        if ($this->driver  == 'mobile') {
            $this->checkLimit();
        }

        //校驗發送頻率
        $this->checkInterval();
        // 生成简讯信息
        $code    = mt_rand(100000, 999999);

        $this->code = $code;
        // 保存简讯信息
        return $this->saveSms($code);
    }


    public function send()
    {
        $sms  = $this->generateCodes();
        try {
            switch ($this->driver) {
                case "mobile":
                    // (new SmsService)->send($this->mobile, $this->type, ['code' => $this->code]);
                    break;
                default:
                    abort(422, '不支持的發送方式');
            }
            $sms->status = 1;
            $sms->save();
        } catch (\Exception $e) {
            $sms->status = 4;
            $sms->error_message = $e->getMessage();
            $sms->save();
            abort(500, '系统错误,请稍后重试');
        }
        return;
    }


    // 校验提交的验证码
    public function checkCode($code = '')
    {
        // 獲取驗證碼信息
        $where = [
            ['type', $this->type],
            ['contact', $this->contact],
            ['code', $code],
            ['status', 1],
        ];
        $sms = Code::where($where)->orderBy('id', 'desc')->first();

        // 驗證碼錯誤
        if (!$sms) {
            abort(422, '验证码错误或已失效');
        }
        // 已超時
        if (strtotime($sms->expire_time) < time()) {
            $sms->status = -2;
            $sms->error_message  = '驗證碼已超時';
            $sms->save();
            abort(422, '驗證碼已超時');
        }
        // 非只讀
        $sms->status      = 2;
        $sms->save();
        // 返回成功
        return true;
    }


    protected function checkInterval($interval = 60)
    {
        $where = [
            ['contact', $this->mobile],
            ['type', $this->type],
            ['status', 1],  // 發送成功
            ['created_at', '>', date('Y-m-d H:i:s', strtotime("-{$interval} seconds"))],
        ];
        $count = Code::where($where)->count();
        if ($count) {
            abort(422, "{$interval}秒內不能重覆發送");
        }
        return true;
    }

    protected function checkLimit($sendLimit = 10)
    {
        $where = [
            ['created_at', '>', now()->startOfDay()],
            ['contact', $this->mobile],
            ['status', 1],  // 發送成功
            ['type', $this->type]
        ];
        $count = Code::where($where)->count();
        if ($count >= $sendLimit) {
            abort(422, "今日發送次數已經達到上限{$sendLimit}次");
        }
        return true;
    }

    protected function saveSms($code)
    {
        $data = [
            'type'      => $this->type,
            'contact'   => $this->contact,
            'driver'    => $this->driver,
            'code'      => $code,
            'expire_time' => Carbon::now()->addMinutes(5)->toDateTimeString(),
        ];
        return Code::create($data);
    }
}
