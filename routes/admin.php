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
    'namespace'  => 'App\Admin\Controllers',
    'middleware' => ['api'],
    'prefix'     => 'admin',
], function ($api) {

    /**
     * 无需登录的接口
     */
    $api->post('/login', 'AdminUserController@login');


    /**
     * 需要登录的接口
     */

    $api->group([
        'middleware' => ['jwt.role:admin']
    ], function ($api) {

        $api->post('/categories', 'CategoryController@store')->name('admin.categories.store');
        $api->get('/categories', 'CategoryController@index')->name('admin.categories.index');
        $api->patch('/categories/{category}/switch', 'CategoryController@switch')->name('admin.categories.switch');
        $api->get('/category_list', 'CategoryController@list')->name('admin.categories.list');

        $api->post('/resources', 'ResourceController@store')->name('admin.resources.store');
        $api->get('/resources', 'ResourceController@index')->name('admin.resources.index');
        $api->patch('/resources/{resource}/switch', 'ResourceController@switch')->name('admin.resources.switch');


        $api->get('/users', 'UserController@index')->name('admin.users.index');
        $api->post('/uploads', 'UploadController@upload')->name('admin.upload.upload');
        $api->post('/url_uploads', 'UploadController@uploadBySrc')->name('admin.upload.url');

        $api->get('/info', 'AdminUserController@info')->name('admin.admin_users.info');


        $api->post('/user_account', 'UserAccountController@addDaysToUser')->name('admin.user_account.store');
    });
});
