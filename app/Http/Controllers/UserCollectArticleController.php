<?php

namespace App\Http\Controllers;

use App\Models\UserCollectArticle;

class UserCollectArticleController extends Controller
{
    public function store()
    {
        UserCollectArticle::create([
            'user_id' => auth('api')->id(),
            'article_id' => request('article_id')
        ]);
        return $this->response()->array(['message' => '收藏成功']);
    }

    public function destroy()
    {
        UserCollectArticle::where([
            'user_id' => auth('api')->id(),
            'article_id' => request('article_id')
        ])->delete();
        return $this->response()->array(['message' => '取消收藏成功']);
    }
}
