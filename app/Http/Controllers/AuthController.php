<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Transformers\UserTransformer;
use App\Services\AuthService;

class AuthController extends Controller
{
    public function register(RegisterRequest $registerRequest, AuthService $authService)
    {
        $user =  $authService->register($registerRequest);
        return $this->response()->item($user, new UserTransformer());
    }


    public function  login(AuthService $authService)
    {
        $result = $authService->login();
        return $this->response()->array($result);
    }
}
