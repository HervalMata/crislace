<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;
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

    /*public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);

        if($validator->fails()){
            return $this->handleError($validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('LaravelSanctumAuth')->plainTextToken;
        $success['name'] =  $user->name;

        return $this->handleResponse($success, 'User successfully registered!');
    }*/

    /**
     * @return UserResource
     */
    public function me()
    {
        $user = Auth::guard('user')->user();
        return new UserResource($user);
    }
}
