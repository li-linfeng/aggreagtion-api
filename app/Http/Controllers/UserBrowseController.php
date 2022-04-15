<?php

namespace App\Http\Controllers;

use App\Models\UserBrowse;
use Illuminate\Http\Request;

class UserBrowseController extends Controller
{
    //

    public function store()
    {
        UserBrowse::create([
            'user_id' => auth('api')->id(),
            'article_id' => request('article_id')
        ]);

        return $this->response()->array(['message' => '浏览成功']);
    }
}
