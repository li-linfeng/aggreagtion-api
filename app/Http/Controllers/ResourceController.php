<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResourceRequest;
use App\Http\Transformers\ResourceTransformer;
use App\Models\Article;
use App\Models\Resource;
use App\Models\UserCollect;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    //创建个人源
    public function store(ResourceRequest $request, ResourceTransformer $resourceTransformer)
    {
        $user_id = auth('api')->id();
        $params = array_merge($request->except('collection_id'), ['user_id' => $user_id]);
        //判断是否已经订阅过
        $exist = Resource::where('link', $request->link)->first();
        if ($exist) {
            if ($exist->user_id == $user_id) {
                abort(422, '已经订阅过此源');
            }
            if (UserCollect::where('resource_id', $exist->id)->where('user_id', $user_id)->first()) {
                abort(422, '已经订阅过此源');
            }
            abort(422, '源市场已存在此源，请前往订阅');
        };

        //获取此源
        $client   = new Client([
            'timeout' => 2.0,
            'verify' => false
        ]);
        try {
            $response = $client->get($request->link);
            //判断header里的content-type 
            $content_type = $response->getHeader('Content-Type');

            if (!preg_match("/rss/", $content_type[0]) || !preg_match("/xml/", $content_type[0])) {
                abort(500, '此链接内容不符合rss规范');
                return;
            }
            //
            $resource = Resource::create($params);
            UserCollect::create([
                'resource_id'   => $resource->id,
                'collection_id' => $request->collection_id,
                'user_id'       => auth('api')->id()
            ]);

            //解析xml并更新文章
            $xml = simplexml_load_string($response->getBody(), 'SimpleXMLElement', LIBXML_NOCDATA);
            $json = json_encode($xml);
            $data = json_decode($json, true);
            $items = $data['channel']['item'] ?? [];

            foreach ($items as $item) {
                Article::firstOrCreate([
                    'title'        => $item['title'] ?? "",
                    'link'         => $item['link'] ?? "",
                    'description'  => $item['description'] ?? "",
                    'resource_id'  => $resource->id,
                    'publish_time' => $item['pubDate'] ? Carbon::parse($item['pubDate'])->toDateTimeString() : Carbon::now()->toDateTimeString(),
                ]);
            }
        } catch (GuzzleException $exception) {
            abort(500, 'rss源的链接无效');
            return;
        }
        return $this->response()->item($resource, $resourceTransformer);
    }



    public function index(Request $request)
    {
        $data =  Resource::filter($request->all())->where('user_id', 0)->paginate();

        return $this->response()->paginator($data, new ResourceTransformer());
    }
}
