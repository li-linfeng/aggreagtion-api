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
        return response()->json(['message' => 'ping']);
    });

    /**
     * 无需登录的接口
     */
    $api->post('/auth/register', 'AuthController@register');
    $api->post('/auth/login', 'AuthController@login');
    $api->post('/codes', 'CodeController@send');


    $api->post('/import', 'ImportController@import')->name('api.source.import');




    /**
     * 需要登录的接口
     */

    $api->group([
        'middleware' => ['auth.jwt']
    ], function ($api) {

        $api->post('/reset_password', 'UserController@resetPassword')->name('api.user.reset_password');
        $api->get('/info', 'UserController@info')->name('api.user.info');

        $api->post('/shares', 'ShareController@store')->name('api.shares.store');
        $api->patch('/share_code', 'UserController@bindShareCode')->name('api.user.share_code');


        $api->post('/collects', 'UserCollectController@store')->name('api.collects.store'); //收藏
        $api->post('/dis_collects', 'UserCollectController@disCollect')->name('api.collects.destroy'); //取消收藏

        $api->post('/play', 'UserPlayRecordController@store')->name('api.user_play_record.store'); //播放资源

        $api->get('/index', 'IndexController@index')->name('api.index.index'); //首页


        $api->get('/channels', 'ChannelController@index')->name('api.channels.index'); //频道列表
        $api->get('/channels/{channel}', 'ChannelController@detail')->name('api.channels.detail'); //频道详情
    });
});
