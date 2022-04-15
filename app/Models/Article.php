<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $guarded = [];



    public function visit()
    {
        return $this->hasOne(UserBrowse::class, 'article_id', 'id')->where('user_id', auth('api')->id());
    }

    public function collect()
    {
        return $this->hasOne(UserCollectArticle::class, 'article_id', 'id')->where('user_id', auth('api')->id());
    }
}
