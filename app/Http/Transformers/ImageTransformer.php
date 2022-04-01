<?php

namespace App\Http\Transformers;

use App\Models\Image;

class ImageTransformer extends BaseTransformer
{

    public function transform(Image $resource)
    {
        return [
            'id'              => $resource->id,
            'url'             => $resource->url,
        ];
    }
}
