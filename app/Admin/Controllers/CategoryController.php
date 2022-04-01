<?php

namespace App\Admin\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Admin\Requests\CategoryRequest;
use App\Admin\Transformers\CategoryTransformer;

class CategoryController extends Controller
{
    public function store(CategoryRequest $request)
    {
        $category = Category::create([
            'name' => $request->input('name')
        ]);

        return $this->response()->item($category, new CategoryTransformer());
    }


    public function index(Request $request)
    {
        $data =  Category::filter($request->all())->paginate();

        return $this->response()->paginator($data, new CategoryTransformer());
    }

    public function list(Request $request)
    {
        $data =  Category::filter($request->all())->get();

        return $this->response()->collection($data, new CategoryTransformer(), ['key' => 'flatten']);
    }


    public function switch(Category $category)
    {
        $category->is_show = $category->is_show ? 0 : 1;
        $category->save();

        return $this->response()->item($category, new CategoryTransformer());
    }
}
