<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\AddressResource;
use App\Models\Address;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;

class AddressController extends BaseController
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'street' => 'required',
            'number' => 'required',
            'district' => 'required',
            'zipcode' => 'required',
            'city' => 'required',
            'uf' => 'required',
        ]);

        if($validator->fails()){
            return $this->handleError($validator->errors());
        }

        $input = $request->all();
        $address = Address::create($input);
        $success['id'] =  $address->id;

        return $this->handleResponse($success, 'Endereço registrado com sucesso!');
    }

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $addresses = Address::all();
        return AddressResource::collection($addresses);
    }
}
