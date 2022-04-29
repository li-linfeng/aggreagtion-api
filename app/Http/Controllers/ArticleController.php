<?php

namespace App\Http\Controllers;

use App\Http\Transformers\ArticleTransformer;
use App\Models\Article;
use App\Models\UserBrowse;
use App\Models\UserCollect;
use App\Models\UserCollectArticle;
use App\Models\UserMuseAccount;
use App\Services\ArticleService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ArticleController extends Controller
{

    //今日更新
    public function newArticles(ArticleService $articleService, ArticleTransformer $articleTransformer)
    {
        $articles = $articleService->newArticles();
        return $this->response()->paginator($articles, $articleTransformer,  [], function ($resource, $fractal) {
            $fractal->parseIncludes(['is_browsed']);
        });
    }


    //所有未读文章
    public function unReadArticles(ArticleService $articleService, ArticleTransformer $articleTransformer)
    {

        $articles = $articleService->unReadArticles();
        return $this->response()->paginator($articles, $articleTransformer,  [], function ($resource, $fractal) {
            $fractal->parseIncludes(['is_browsed']);
        });
    }

    //星标文章
    public function collectArticles(ArticleService $articleService, ArticleTransformer $articleTransformer)
    {
        $articles = $articleService->collectArticles();
        return $this->response()->paginator($articles, $articleTransformer,  [], function ($resource, $fractal) {
            $fractal->parseIncludes(['is_browsed', 'is_collected']);
        });
    }


    //正在阅读
    public function history(ArticleService $articleService, ArticleTransformer $articleTransformer)
    {
        $articles = $articleService->history();
        return $this->response()->collection($articles, $articleTransformer, ['key' => 'flatten'], function ($resource, $fractal) {
            $fractal->parseIncludes(['is_browsed', 'is_collected']);
        });
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
        //查看账户是否到期

        $account = UserMuseAccount::where('user_id', auth('api')->id())->first();
        if ($account && $account->expire_time < Carbon::now()) {
            return abort(403, '账户已到期');
        }
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


    public function getUserArticleData(ArticleService $articleService)
    {
        $new_article_count = $articleService->newArticles(true);
        $un_read_count = $articleService->unReadArticles(true);
        $collect_count = $articleService->collectArticles(true);
        $reading_count = $articleService->history(true);
        return $this->response()->array([
            'new_article_count' => $new_article_count,
            'un_read_count'     => $un_read_count,
            'collect_count'     => $collect_count,
            'reading_count'     => $reading_count,
        ]);
    }
}
