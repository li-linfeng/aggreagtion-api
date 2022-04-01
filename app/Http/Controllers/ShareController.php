<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShareRequest;
use App\Services\ShareService;

class ShareController extends Controller
{

    public function store(ShareRequest $request, ShareService $service)
    {
        $left = $service->share($request->input('channel', ''));
        return $this->response()->array(compact('left'));
    }
}
