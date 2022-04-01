<?php

namespace App\Models;

use App\Models\Filters\CategoryFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, CategoryFilter;

    protected $guarded = [];
}
