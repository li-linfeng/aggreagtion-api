<?php

namespace App\Http\Transformers;

use App\Models\Resource;
use Carbon\Carbon;

class MediaTransformer extends BaseTransformer
{

    protected $availableIncludes = ['cover_image', 'play_list', 'is_collect'];

    public function transform(Resource $resource)
    {

        $route = request()->route()->getName();
        switch ($route) {
            case "api.index.index":
            case "api.channels.detail":
                return [
                    'id'          => $resource->id,
                    'type'        => $resource->type,
                    'title'       => $resource->title,
                    'url'         => $resource->url,
                    'total_time'  => transformTime($resource->total_time),
                    'description' => $resource->description,
                ];
            default:
                return [
                    'id'          => $resource->id,
                    'name'        => $resource->name,
                ];
        }
    }


    public function includeCoverImage(Resource $resource)
    {
        if (!$resource->coverImage) {
            return $this->nullObject();
        }
        return $this->item($resource->coverImage, new ImageTransformer);
    }

    public function includePlayList(Resource $resource)
    {
        if (!$resource->playList) {
            return $this->nullObject();
        }
        return $this->item($resource->coverImage, new ImageTransformer);
    }

    //是否收藏
    public function includeIsCollect(Resource $resource)
    {
        if (!$resource->isCollect) {
            return $this->primitive(false);
        }
        return $this->primitive(true);
    }
}
