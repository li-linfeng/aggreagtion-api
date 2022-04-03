<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCollect extends Model
{

    protected $table = 'user_collections';

    use HasFactory;

    protected $guarded = [];
}
