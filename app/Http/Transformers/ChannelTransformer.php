<?php

namespace App\Http\Transformers;

use App\Models\Channel;
use Carbon\Carbon;

class ChannelTransformer extends BaseTransformer
{

    protected $availableIncludes = ['default_list', 'special_list', 'background_image', 'icon_choose', 'icon_unchoose', 'other_list', 'default_list_medias', 'special_list_medias'];

    public function transform(Channel $channel)
    {
        $route = request()->route()->getName();
        switch ($route) {
            default:
                return [
                    'id'          => $channel->id,
                    'name'        => $channel->name,
                    'description' => $channel->description,
                ];
        }
    }


    public function includeDefaultList(Channel $channel)
    {
        if (!$channel->defaultList) {
            return $this->nullObject();
        }
        return $this->item($channel->defaultList, new PlayListTransformer);
    }

    public function includeDefaultListMedias(Channel $channel)
    {
        if (!$channel->defaultListResource) {
            return $this->null();
        }
        return $this->collection($channel->defaultListResource, new MediaTransformer, 'flatten');
    }

    public function includeSpecialList(Channel $channel)
    {
        if (!$channel->specialList) {
            return $this->nullObject();
        }
        return $this->item($channel->specialList, new PlayListTransformer);
    }


    public function includeSpecialListMedias(Channel $channel)
    {
        if (!$channel->specialListResource) {
            return $this->null();
        }
        return $this->collection($channel->specialListResource, new MediaTransformer, 'flatten');
    }

    public function includeBackgroundImage(Channel $channel)
    {
        if (!$channel->backgroundImage) {
            return $this->nullObject();
        }
        return $this->item($channel->backgroundImage, new ImageTransformer);
    }

    public function includeIconChoose(Channel $channel)
    {
        if (!$channel->iconChoose) {
            return $this->nullObject();
        }
        return $this->item($channel->iconChoose, new ImageTransformer);
    }

    public function includeIconUnchoose(Channel $channel)
    {
        if (!$channel->iconUnchoose) {
            return $this->nullObject();
        }
        return $this->item($channel->iconUnchoose, new ImageTransformer);
    }

    public function includeOtherList(Channel $channel)
    {
        if ($channel->playList->isEmpty()) {
            return $this->null();
        }

        $list = $channel->playList->filter(function ($item) {
            return !$item->is_default && !$item->is_special;
        })->values();
        return $this->collection($list, new PlayListTransformer, 'flatten');
    }
}
