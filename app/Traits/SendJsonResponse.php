<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait SendJsonResponse
{
    /**
     * @param $params
     * @return JsonResponse
     */
    public function sendJsonResponse($params): JsonResponse
    {
        return response()->json(['data' => $params]);
    }
}
