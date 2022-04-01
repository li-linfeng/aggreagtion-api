<?php

namespace App\Http\Transformers;

use App\Models\PlayList;
use Carbon\Carbon;
use App\Http\Transformers\MediaTransformer;

class PlayListTransformer extends BaseTransformer
{

    protected $availableIncludes = ['channel', 'medias'];

    public function transform(PlayList $list)
    {
        $route = request()->route()->getName();
        switch ($route) {
            default:
                return [
                    'id'                  => $list->id,
                    'name'                => $list->name,
                ];
        }
    }


    public function includeChannel(PlayList $list)
    {
        if (!$list->channel) {
            return $this->nullObject();
        }
        return $this->item($list->channel, new ChannelTransformer);
    }

    public function includeMedias(PlayList $list)
    {

        if ($list->medias->isEmpty()) {
            return $this->null();
        }
        return $this->collection($list->medias, new MediaTransformer, 'flatten');
    }
}
