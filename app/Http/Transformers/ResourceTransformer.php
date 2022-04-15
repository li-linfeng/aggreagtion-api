<?php

namespace App\Http\Transformers;

use App\Models\Resource;

class ResourceTransformer extends BaseTransformer
{

    protected $availableIncludes = ['category'];

    public function transform(Resource $resource)
    {
        $route = request()->route()->getName();
        switch ($route) {
            case "api.user.collections.list":
                return [
                    'id'             => $resource->id,
                    'name'           => $resource->name,
                    'un_visit_count' => $resource->un_visit_count,
                ];
            default:
                return [
                    'id'          => $resource->id,
                    'name'        => $resource->name,
                    'is_show'     => $resource->is_show,
                    'link'        => $resource->link,
                    'description' => $resource->description
                ];
        }
    }


    public function includeCategory(Resource $resource)
    {
        if (!$resource->category) {
            return $this->nullObject();
        }
        return $this->item($resource->category, new CategoryTransformer);
    }
}
