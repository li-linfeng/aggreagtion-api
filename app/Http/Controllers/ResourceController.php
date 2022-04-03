<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResourceRequest;
use App\Http\Transformers\ResourceTransformer;
use App\Models\Resource;
use App\Models\UserCollect;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    //创建个人源
    public function store(ResourceRequest $request, ResourceTransformer $resourceTransformer)
    {
        $params = array_merge($request->except('collection_id'), ['user_id' => auth('api')->id()]);
        $resource = Resource::create($params);
        UserCollect::create([
            'resource_id'   => $resource->id,
            'collection_id' => $request->collection_id,
            'user_id'       => auth('api')->id()
        ]);
        return $this->response()->item($resource, $resourceTransformer);
    }



    public function index(Request $request)
    {
        $data =  Resource::filter($request->all())->where('user_id', 0)->paginate();

        return $this->response()->paginator($data, new ResourceTransformer());
    }
}
