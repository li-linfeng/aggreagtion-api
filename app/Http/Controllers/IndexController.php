<?php

namespace App\Http\Controllers;

use App\Http\Transformers\ChannelTransformer;
use App\Models\Channel;
use App\Services\ChannelService;
use App\Services\IndexService;

class IndexController extends Controller
{
    public function index(IndexService $indexService, ChannelService $channelService, ChannelTransformer $channelTransformer)
    {
        $channel = Channel::where('is_index', 1)->first();
        $recent = $channelService->detail($channel);
        $like = $indexService->index();
        $channel->load(['playList.medias.isCollect', 'playList.medias.coverImage']);
        return  $this->response()->item($channel, $channelTransformer, [], function ($resource, $fractal) {
            $fractal->parseIncludes(['other_list.medias.is_collect', 'other_list.medias.cover_image']);
        })->setMeta(['recent' => $recent, 'like' => $like]);
    }
}
