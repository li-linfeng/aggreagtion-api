<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\Resource;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

class UpdateArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_articles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update_articles';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        app('log')->info('开始同步所有源的文章数据');
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

        app('log')->info('同步所有源的文章数据结束');
    }
}
