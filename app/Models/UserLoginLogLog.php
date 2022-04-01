<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLoginLogLog extends Model
{

    public $table = "user_login_log";

    protected $guarded = [];


    public function  user()
    {
        return $this->belongsTo(User::class,  'user_id','id');
    }
}
