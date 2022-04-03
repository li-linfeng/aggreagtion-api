<?php

namespace App\Models\Filters;


trait ResourceFilter
{
    use BaseFilter;

    // 类型
    public function filterName($name = '')
    {
        if (!$name) {
            return;
        }
        return $this->builder->where('name', 'like', "%{$name}%");
    }

    // 类型
    public function filterIsShow($is_show = '')
    {
        if (!$is_show) {
            return;
        }
        return $this->builder->where('is_show', $is_show);
    }


    public function filterCategoryId($id = '')
    {
        if (is_null($id)) {
            return;
        }
        return $this->builder->where('category_id', $id);
    }
}
