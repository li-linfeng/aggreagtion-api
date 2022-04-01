<?php

namespace App\Http\Services;

use Mrgoon\AliSms\AliSms;



class SmsService
{

    protected $sms_codes = [
        'find_pwd'  => 'SMS_231448137',
    ];


    public function send($mobile, $type, $params)
    {
        $aliSms = new AliSms();
        $temp  =  $aliSms->sendSms($mobile,  $this->sms_codes[$type], $params);
        return true;
    }
}
