<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use http\Env\Response;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    /**
     * @param Request $request
     * @return Application|ResponseFactory|\Illuminate\Http\Response
     */
    protected function sendResetLinkResponse(Request $request): \Illuminate\Http\Response|Application|ResponseFactory
    {
        $input = $request->only('email');

        $validator = Validator::make($input, [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all(), 422]);
        }

        $response = Password::sendResetLink($input);

        if ($response == Password::RESET_LINK_SENT) {
            $message = "Email enviado com sucesso";
        } else {
            $message = "Email não pode ser enviado para este endereço de email";
        }

        $response = ['data' => '', 'message' => $message];

        return response($response, 200);
    }

    protected function sendResetResponse(Request $request)
    {
        $input = $request->only('email', 'token', 'password', 'password_confirmation');

        $validator = Validator::make($input, [
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|confirmed|min:8'
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all(), 422]);
        }

        $response = Password::reset($input, function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->save();
            event(new PasswordReset($user));
        });

        if ($response == Password::PASSWORD_RESET) {
            $message = "Senha alterada com sucesso";
        } else {
            $message = "Email não pode ser enviado para este endereço de email";
        }

        $response = ['data' => '', 'message' => $message];

        return response()->json($response);
    }
}
