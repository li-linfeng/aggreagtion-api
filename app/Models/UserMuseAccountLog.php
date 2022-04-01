<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMuseAccountLog extends Model
{

    public $table = "user_muse_account_log";

    protected $guarded = [];


    public function  user()
    {
        return $this->belongsTo(User::class,  'user_id','id');
    }


    public function  account()
    {
        return $this->belongsTo(UserMuseAccount::class,  'account_id','id');
    }
}
