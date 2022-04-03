<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;

    protected $guarded = [];



    public function resources()
    {
        return $this->hasManyThrough(Resource::class, UserCollect::class, 'collection_id', 'id', 'id', 'resource_id');
    }
}
