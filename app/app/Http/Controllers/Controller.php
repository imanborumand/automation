<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponseTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ApiResponseTrait;



    /**
     * generate default api response
     *
     * @param object|array|bool|null $response
     * @param int|null               $httpCode
     * @return JsonResponse
     */
    protected function apiResponse(mixed $response, int $httpCode = null): JsonResponse
    {
        $response = $this->successResponse()->setData($response);

        if ($httpCode) {
            $response = $response->setHttpCode($httpCode)->setStatusCode($httpCode);
        }

        return $response->response();
    }

}
