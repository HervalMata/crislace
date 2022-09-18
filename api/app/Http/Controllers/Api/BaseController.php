<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    /**
     * @param $result
     * @param $msg
     * @return JsonResponse
     */
    public function handleResponse($result, $msg): JsonResponse
    {
        $res = [
            'success' => true,
            'data' => $result,
            'message' => $msg,
        ];
        return response()->json($res, 200);
    }

    /**
     * @param $error
     * @param $errorMsg
     * @param $code
     * @return JsonResponse
     */
    public function handleError($error, array $errorMsg = [], int $code = 404): JsonResponse
    {
        $res = [
            'success' => false,
            'message' => $error,
        ];
        if (!empty($errorMsg)) {
            $res['data'] = $errorMsg;
        }
        return response()->json($res, $code);
    }
}
