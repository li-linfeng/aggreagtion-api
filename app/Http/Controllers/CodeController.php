<?php

namespace App\Http\Controllers;

use App\Http\Requests\CodeRequest;
use App\Services\CodeService;
use Illuminate\Http\Request;

class CodeController extends Controller
{
    public function send(CodeRequest $codeRequest, CodeService $codeService)
    {
        $codeService->setData([
            'driver'  => $codeRequest->input('driver', 'mobile'),
            'type'    => $codeRequest->input('type', 'register'),
            'contact' => $codeRequest->contact,
        ]);
        $codeService->send();
        return $this->response->noContent();
    }
}
