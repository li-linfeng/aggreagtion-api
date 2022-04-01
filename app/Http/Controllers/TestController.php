<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resource;

class TestController extends Controller
{
    public function test(Request $request)
    {

        $resource = Resource::find(115);
        // $url = $resource->url;
        // $api_user = auth('api')->user();
        // if ($api_user) {
        //     $key = $api_user->id . '_' . $api_user->nickname;
        //     $url = encryptUrl($url, $key);
        // }
        $key = "6_15767976547";
        $url = "1zB1SrLh0rE0B107litHk00zPxWIX/+ysp84KM6SlBgTxzXWk4uCZEtCSYBrLP9o6rNff4+E0a6UO21qoUm1/uTBYDqJmSKHyKGXeuk9jho=";
        $origin = decryptUrl($url, $key);
        dd($origin);
    }
}
