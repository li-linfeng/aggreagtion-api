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

        return $this->response()->array(['message' => '提交成功']);
    }


    public function  userCollections(CollectionTransformer $collectionTransformer)
    {
        $items = Collection::where('user_id', auth('api')->id())
            // ->withCount(['resources.articles.visit', 'resources.articles'])
            ->with([
                'resources.articles' => function ($query) {
                    $query->withCount('visit');
                },
                'resources' => function ($query) {
                    $query->withCount('articles');
                }
            ])
            ->get()
            ->map(function ($item) {
                $item->resources->map(function ($resource) {
                    $total_visited = $resource->articles->sum('visit_count');
                    $resource->un_visit_count = $resource->articles_count - $total_visited;
                    return $resource;
                });
                return $item;
            });

        return $this->response()->collection($items, $collectionTransformer, ['key' => 'flatten'], function ($resource, $fractal) {
            $fractal->parseIncludes(['resources']);
        });
    }
}
