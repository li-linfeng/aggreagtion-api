<?php

namespace App\Http\Controllers;

use App\Http\Requests\CodeRequest;
use App\Models\Code;
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

        return $this->response()->array(['message' => '提交成功']);
    }


    public  function  getCodeByMobile(Request $request)
    {
        $code  = Code::where('contact', $request->mobile)
            ->where('status', 1)
            ->where('type', $request->type)
            ->orderBy('created_at', 'desc')
            ->first();
        return $this->response->array($code->toArray());
    }
}
