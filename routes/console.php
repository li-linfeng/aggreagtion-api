<?php

use App\Models\Article;
use App\Models\Resource;
use Illuminate\Support\Facades\Artisan;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('addArticles', function () {
    $resources = Resource::where('is_show', 1)->get()->map(function ($resource) {
        //获取发送消息的签名
        $client   = new Client([
            'timeout' => 2.0,
            'verify' => false
        ]);
        try {
            $response = $client->get($resource->link);
            //判断header里的content-type 
            $content_type = $response->getHeader('Content-Type');

            if (!preg_match("/rss/", $content_type[0]) || !preg_match("/xml/", $content_type[0])) {
                $resource->update(['is_show' => 0]);
                return;
            }
            //解析xml并更新文章
            $xml = simplexml_load_string($response->getBody(), 'SimpleXMLElement', LIBXML_NOCDATA);
            $json = json_encode($xml);
            $data = json_decode($json, true);
            $items = $data['channel']['item'] ?? [];

            foreach ($items as $item) {
                Article::firstOrCreate([
                    'title'       => $item['title'] ?? "",
                    'link'        => $item['link'] ?? "",
                    'description' => $item['description'] ?? "",
                    'resource_id' => $resource->id,
                    'publish_time' => $item['pubDate'] ? Carbon::parse($item['pubDate'])->toDateTimeString() : Carbon::now()->toDateTimeString(),
                ]);
            }
        } catch (GuzzleException $exception) {
            $resource->update(['is_show' => 0]);
        }
    });
});
