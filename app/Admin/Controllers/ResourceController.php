<?php

namespace App\Admin\Controllers;

use Illuminate\Http\Request;
use App\Admin\Requests\ResourceRequest;
use App\Admin\Transformers\ResourceTransformer;
use App\Models\Resource;

class ResourceController extends Controller
{
    public function store(ResourceRequest $request)
    {
        $category = Resource::create($request->all());

        return $this->response()->item($category, new ResourceTransformer());
    }


    public function index(Request $request)
    {
        $data =  Resource::filter($request->all())->paginate();

        return $this->response()->paginator($data, new ResourceTransformer());
    }


    public function switch(Resource $resource)
    {
        $resource->is_show = $resource->is_show ? 0 : 1;
        $resource->save();

        return $this->response()->item($resource, new ResourceTransformer());
    }
}
