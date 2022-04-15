<?php

namespace App\Models;

use App\Models\Filters\ResourceFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory, ResourceFilter;


    protected $guarded = [];



    public function category()
    {
        return $this->belongsTo(Category::class, 'id', 'category_id');
    }

    public function articles()
    {
        return $this->hasMany(Article::class, 'resource_id', 'id');
    }
}
