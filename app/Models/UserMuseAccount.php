<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class UserMuseAccount extends Model
{

    public $table = "user_muse_account";

    protected $guarded = [];


    public function  user()
    {
        return $this->belongsTo(User::class,  'user_id','id');
    }


    public function  addLogs()
    {
        return $this->hasMany(UserMuseAccountLog::class,  'id','account_id');
    }


    public function getIsExpiredAttribute()
    {
        $now = Carbon::now()->toDateTimeString();
        if($now >= $this->end_time || !$this->end_time){ //如果已过期，或者刚初始化则都认为是过期
            return true;
        }
        return false;
    }
}
