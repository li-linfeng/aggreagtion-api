<?php

namespace App\Http\Controllers;

use App\Http\Transformers\ArticleTransformer;
use App\Models\Article;
use App\Models\UserBrowse;
use App\Models\UserCollect;
use App\Models\UserCollectArticle;
use Illuminate\Http\Request;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ArticleController extends Controller
{

    //今日更新
    public function newArticles(ArticleTransformer $articleTransformer)
    {

        $resource_ids = UserCollect::where('user_id', auth('api')->id())->pluck('resource_id')->toArray();
        $articles = Article::where('publish_time', '>', Carbon::today()->toDateTimeString())
            ->with(['visit'])
            ->whereIn('resource_id', $resource_ids)
            // ->whereDoesntHave('visit')
            ->paginate(request('per_page', 10));
        return $this->response()->paginator($articles, $articleTransformer,  [], function ($resource, $fractal) {
            $fractal->parseIncludes(['is_browsed']);
        });
    }


    //今日更新
    public function unReadArticles(ArticleTransformer $articleTransformer)
    {

        $resource_ids = UserCollect::where('user_id', auth('api')->id())->pluck('resource_id')->toArray();
        $articles = Article::whereIn('resource_id', $resource_ids)
            ->with(['visit'])
            ->whereDoesntHave('visit')
            ->paginate(request('per_page', 10));
        return $this->response()->paginator($articles, $articleTransformer,  [], function ($resource, $fractal) {
            $fractal->parseIncludes(['is_browsed']);
        });
    }

    //星标文章
    public function collectArticles(ArticleTransformer $articleTransformer)
    {

        $resource_ids = UserCollectArticle::where('user_id', auth('api')->id())->pluck('article_id')->toArray();
        $articles = Article::whereIn('id', $resource_ids)
            ->with(['visit', 'collect'])
            ->paginate(request('per_page', 10));
        return $this->response()->paginator($articles, $articleTransformer,  [], function ($resource, $fractal) {
            $fractal->parseIncludes(['is_browsed', 'is_collected']);
        });
    }


    //正在阅读
    public function history(ArticleTransformer $articleTransformer)
    {
        $ids = UserBrowse::where('user_id', auth('api')->id())->orderBy('created_at', 'desc')->take(10)->pluck('article_id')->toArray();
        $articles =  Article::when($ids, function ($q) use ($ids) {
            $id_str = implode(",", $ids);
            $q->whereIn('id',  $ids)->orderByRaw(\DB::raw("FIELD(id, $id_str)"));
        }, function ($q) {
            $q->where('id',  0);
        })
            ->with(['visit', 'collect'])
            ->get();

        return $this->response()->collection($articles, $articleTransformer, ['key' => 'flatten'], function ($resource, $fractal) {
            $fractal->parseIncludes(['is_browsed', 'is_collected']);
        })->setMeta([
            'total' => count($ids),
        ]);
    }


    public function getArticleByResource(Request $request, ArticleTransformer $articleTransformer)
    {
        $articles = Article::where('resource_id', $request->resource_id)
            ->with(['visit', 'collect'])
            ->orderBy('publish_time', 'desc')
            ->paginate(request('per_page', 10));

        return $this->response()->paginator($articles, $articleTransformer,  [], function ($resource, $fractal) {
            $fractal->parseIncludes(['is_browsed', 'is_collected']);
        });
    }


    public function show(Article $article)
    {
        //获取发送消息的签名
        $client   = new Client([
            'verify' => false
        ]);
        try {
            $response = $client->get($article->link);
            $body = $response->getBody();
            $content = $body->getContents();
        } catch (GuzzleException $exception) {
            abort(500, $exception->getMessage());
        }
        return $this->response()->array([
            'publish_time' => $article->publish_time,
            'id'           => $article->id,
            'title'        => $article->title,
            'detail'       => $content,
            'is_collected' => $article->collect ? true : false,
            'is_browsed'   => $article->visit ? true : false,
        ]);
    }
}
