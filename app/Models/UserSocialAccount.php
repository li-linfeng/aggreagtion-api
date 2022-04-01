<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSocialAccount extends Model
{

    public $table = "user_social_account";

    protected $guarded = [];


    public function  user()
    {
        return $this->belongsTo(User::class,  'user_id', 'id');
    }
}
