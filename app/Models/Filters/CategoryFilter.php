<?php

namespace App\Models\Filters;


trait CategoryFilter
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
}
