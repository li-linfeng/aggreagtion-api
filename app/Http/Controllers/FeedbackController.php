<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{


    public function store(Request $request)
    {

        Feedback::create([
            'content' => $request->content,
            'user_id' => auth('api')->id()
        ]);

        return $this->response()->array(['message' => '提交成功']);
    }
}
