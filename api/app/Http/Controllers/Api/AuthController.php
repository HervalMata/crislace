<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseController
{
    /**
     * @param Request $request
     * @return JsonResponse | null
     */
    public function login(Request $request): ?JsonResponse
    {
        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {
            $user = Auth::guard('user')->user();
            $success['token'] = $user->createToken('CrisLace', ['user'])->plainTextToken;
            $success['name'] = $user->name;
            return $this->handleResponse($success, 'Usuário Logado');
        } else {
            return $this->handleError('Usuário não autorizado', ['error' => 'Unauthorised']);
        }
    }

    /**
     * @return void
     */
    public function logout(): void
    {
        $user = Auth::guard('user')->user();
        $user->tokens()->delete();
    }
}
