<?php

namespace App\Http\Controllers;

use App\Http\Transformers\CollectionTransformer;
use App\Models\Collection;
use Illuminate\Http\Request;

class CollectionController extends Controller
{


    //私人分类列表
    public function list(CollectionTransformer $collectionTransformer)
    {
        $items = Collection::where('user_id', auth('api')->id())->get();
        return $this->response()->collection($items, $collectionTransformer, ['key' => 'flatten']);
    }

    //创建收藏夹
    public function store(Request $request)
    {
        Collection::create([
            'name'    => $request->name,
            'user_id' => auth('api')->id()
        ]);
        return $this->response()->noContent();
    }


    public function  userCollections(CollectionTransformer $collectionTransformer)
    {
        $items = Collection::where('user_id', auth('api')->id())
            ->with(['resources'])
            ->get();

        return $this->response()->collection($items, $collectionTransformer, ['key' => 'flatten'], function ($resource, $fractal) {
            $fractal->parseIncludes(['resources']);
        });
    }
}
