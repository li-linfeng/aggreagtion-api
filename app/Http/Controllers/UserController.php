<?php

namespace App\Http\Controllers;

use App\Http\Transformers\UserTransformer;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use App\Services\CodeService;

class UserController extends Controller
{
    public function  info(UserTransformer $userTransformer)
    {

        $info = auth('api')->user();
        $userTransformer->setDefaultIncludes(['user_account']);
        return $this->response()->item($info, $userTransformer);
    }

    public function resetPassword(ResetPasswordRequest $request, CodeService $codeService)
    {
        $user = User::where('mobile', $request->contact)->first();
        if (!$user) {
            abort(422, "该手机未注册");
        }

        $codeService->setData([
            'driver'  => 'mobile',
            'type'    => 'find_pwd',
            'contact' => $request->contact,
        ]);
        $codeService->checkCode($request->code);

        $user->update(['password' => bcrypt($request->password)]);

        return $this->response()->array(['message' => '提交成功']);
    }
}
