<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');


$api->version('v1', [
    'namespace'  => 'App\Http\Controllers',
    'middleware' => ['api'],
    'prefix'     => 'api',
], function ($api) {


    $api->get('/ping', function () {
        // abort(404, '接口不存在');
        return response()->json(['message' => 'ping']);
    });

    /**
     * 无需登录的接口
     */
    $api->post('/auth/register', 'AuthController@register');
    $api->post('/auth/login', 'AuthController@login');
    $api->post('/codes', 'CodeController@send');
    $api->get('/mobile_codes', 'CodeController@getCodeByMobile');


    $api->post('/import', 'ImportController@import')->name('api.source.import');




    /**
     * 需要登录的接口
     */

    $api->group([
        'middleware' => ['auth.jwt']
    ], function ($api) {

        $api->post('/reset_password', 'UserController@resetPassword')->name('api.user.reset_password');
        $api->get('/info', 'UserController@info')->name('api.user.info');


        $api->post('/feedbacks', 'FeedbackController@store')->name('api.feedbacks.store');

        //收藏夹
        $api->post('/collections', 'CollectionController@store')->name('api.collection.store');
        $api->get('/collection_list', 'CollectionController@list')->name('api.collection.list');

        $api->post('/user_collect', 'UserCollectController@store')->name('api.user_collect.store');

        //源市场分类
        $api->get('/categories', 'CategoryController@index')->name('api.categories.index');
        $api->get('/category_list', 'CategoryController@list')->name('api.categories.list');

        //源列表
        $api->get('/resources', 'ResourceController@index')->name('api.resources.index');


        //创建个人源
        $api->post('/user/resources', 'ResourceController@store')->name('api.user.resources.store');
        $api->get('/user/collections', 'CollectionController@userCollections')->name('api.user.collections.list');


        //今日更新文章
        $api->get('/new_articles', 'ArticleController@newArticles')->name('api.articles.new');
        $api->get('/unread_articles', 'ArticleController@unReadArticles')->name('api.articles.unread');
        $api->get('/collect_articles', 'ArticleController@collectArticles')->name('api.articles.collect');
        $api->get('/history_articles', 'ArticleController@history')->name('api.articles.history');

        //收藏文章
        $api->post('/collect/article', 'UserCollectArticleController@store')->name('api.collect.store');
        $api->delete('/collect/article', 'UserCollectArticleController@destroy')->name('api.collect.delete');

        $api->get('/articles', 'ArticleController@getArticleByResource')->name('api.articles.resource');
        $api->get('/articles/{article}', 'ArticleController@show')->name('api.articles.show');

        $api->get('/article_data', 'ArticleController@getUserArticleData')->name('api.user.article_data');


        //浏览
        $api->post('/user_browses', 'UserBrowseController@store')->name('api.user_browse.store');
    });
});
