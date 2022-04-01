<?php

namespace App\Admin\Transformers;

use App\Models\Category;

class CategoryTransformer extends BaseTransformer
{

    public function transform(Category $category)
    {
        return [
            'id'      => $category->id,
            'name'    => $category->name,
            'is_show' => $category->is_show,
        ];
    }
}
