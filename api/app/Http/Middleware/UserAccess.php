<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param $userType
     * @return JsonResponse
     */
    public function handle(Request $request, Closure $next, $userType): JsonResponse
    {
        if (auth()->user()->type == $userType) {
            return $next($request);
        }
        return response()->json(['Você não está autorizado.']);
    }
}
