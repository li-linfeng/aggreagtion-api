<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\ResourceImport;

class ImportController extends Controller
{
    public function import(Request $request)
    {
        app('excel')->import(new ResourceImport(), $request->file('file'));

        return $this->response()->array(['message' => '提交成功']);
    }
}
