<?php

namespace  App\Services;

use App\Http\Transformers\MediaTransformer;
use App\Models\UserCollect;
use App\Serializers\CustomSerializer;
use Carbon\Carbon;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;

class IndexService
{

    public function __construct()
    {
        $this->fractal             = new Manager();
        $this->resourceTransformer = new MediaTransformer();
        $this->fractal->setSerializer(new CustomSerializer);
    }

    public function index()
    {
        $user_id = auth('api')->id();
        return $this->getLike($user_id);
    }


    protected function getLike($user_id)
    {
        $like_ids = $this->getLikePlayIds($user_id);
        if (!$like_ids) {
            return [];
        }
        $recent =  app(ResourceService::class)->getResourceRecentListById($like_ids);
        $resources = $this->transformToCollection($recent, ['cover_image', 'is_collect']);
        return $resources;
    }


    protected function  transformToCollection($data, $includes)
    {
        $this->fractal->parseIncludes($includes);
        $resource = new Collection($data, $this->resourceTransformer, 'flatten');
        return  $this->fractal->createData($resource)->toArray();
    }



    protected function getLikePlayIds($user_id)
    {
        $ids = UserCollect::where('user_id', $user_id)
            ->orderByDesc('created_at')
            ->pluck('resource_id')
            ->toArray();
        return $ids;
    }
}
