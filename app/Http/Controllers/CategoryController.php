<?php

namespace App\Http\Controllers;

use App\Http\Transformers\CategoryTransformer;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    //公共分类
    public function index(Request $request, CategoryTransformer $categoryTransformer)
    {

        $items = Category::where('user_id', 0)->where('is_show', 1)->paginate(request('per_page', 10));
        return $this->response()->paginator($items, $categoryTransformer);
    }

    //公共分类
    public function list(Request $request, CategoryTransformer $categoryTransformer)
    {
        $items = Category::where('user_id', 0)->where('is_show', 1)->get();
        return $this->response()->collection($items, $categoryTransformer, ['key' => 'flatten']);
    }
}
