<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileAddressRequest;
use App\Http\Resources\ProfileAddressResource;
use App\Models\Address;
use App\Models\Profile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileAddressController extends Controller
{
    /**
     * @param Profile $profile
     * @return ProfileAddressResource
     */
    public function index(Profile $profile): ProfileAddressResource
    {
        return new ProfileAddressResource($profile);
    }

    /**
     * @param ProfileAddressRequest $request
     * @param Profile $profile
     * @return array|JsonResponse
     */
    public function store(ProfileAddressRequest $request, Profile $profile): JsonResponse|array
    {
        $changed = $profile->addresses()->sync($request->addresses);
        $addressesAttachedId = $changed['attached'];
        $addresses = Address::whereIn('id', $addressesAttachedId)->get();
        return $addresses->count() ? response()->json(new ProfileAddressResource($profile), 201) : [];
    }
}
