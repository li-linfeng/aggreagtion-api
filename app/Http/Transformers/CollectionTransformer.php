<?php

namespace App\Http\Transformers;

use App\Models\Collection;

class CollectionTransformer extends BaseTransformer
{


    protected $availableIncludes = ['resources'];

    public function transform(Collection $collection)
    {
        return [
            'id'      => $collection->id,
            'name'    => $collection->name,
        ];
    }


    public function includeResources(Collection $collection)
    {
        if ($collection->resources->isEmpty()) {
            return $this->null();
        }
        return $this->collection($collection->resources, new ResourceTransformer, 'flatten');
    }
}
