<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * @param Request $request
     * @return array
     */
    public function update(Request $request): array
    {
        $data = $request->all();
        if ($request->has('remove_photo')) {
            $data['image'] = null;
        }
        $user = Auth::guard('user')->user();
        $user->updateWithProfile($data);
        $resource = new UserResource($user);
        return [
            'user' => $resource->toArray($request),
            'token' => Auth::guard('user')->login($user)
        ];
    }
}
