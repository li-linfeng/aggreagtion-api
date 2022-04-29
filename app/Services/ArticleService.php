<?php

namespace App\Services;

use App\Models\Article;
use App\Models\UserBrowse;
use App\Models\UserCollect;
use App\Models\UserCollectArticle;
use Carbon\Carbon;

class  ArticleService
{
    //今日更新
    public function newArticles($need_count = false)
    {
        $resource_ids = UserCollect::where('user_id', auth('api')->id())->pluck('resource_id')->toArray();
        $builder  = Article::where('created_at', '>', Carbon::today()->toDateTimeString())
            ->with(['visit'])
            ->whereIn('resource_id', $resource_ids);
        if ($need_count) {
            return $builder->count();
        } else {
            return $builder->paginate(request('per_page', 10));
        }
    }


    //所有未读文章
    public function unReadArticles($need_count = false)
    {

        $resource_ids = UserCollect::where('user_id', auth('api')->id())->pluck('resource_id')->toArray();
        $builder = Article::whereIn('resource_id', $resource_ids)
            ->with(['visit'])
            ->whereDoesntHave('visit');
        if ($need_count) {
            return $builder->count();
        } else {
            return $builder->paginate(request('per_page', 10));
        }
    }

    //星标文章
    public function collectArticles($need_count = false)
    {

        $resource_ids = UserCollectArticle::where('user_id', auth('api')->id())->pluck('article_id')->toArray();
        $builder = Article::whereIn('id', $resource_ids)
            ->with(['visit', 'collect']);
        if ($need_count) {
            return $builder->count();
        } else {
            return $builder->paginate(request('per_page', 10));
        }
    }


    //正在阅读
    public function history($need_count = false)
    {
        $ids = UserBrowse::where('user_id', auth('api')->id())->orderBy('created_at', 'desc')->take(10)->pluck('article_id')->toArray();
        $builder =  Article::when($ids, function ($q) use ($ids) {
            $id_str = implode(",", $ids);
            $q->whereIn('id',  $ids)->orderByRaw(\DB::raw("FIELD(id, $id_str)"));
        }, function ($q) {
            $q->where('id',  0);
        })
            ->with(['visit', 'collect']);
        if ($need_count) {
            return $builder->count();
        } else {
            return $builder->paginate(request('per_page', 10));
        }
    }
}
