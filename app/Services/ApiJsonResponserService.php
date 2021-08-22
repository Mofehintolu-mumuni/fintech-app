<?php

namespace App\Services;

use Illuminate\HTTP\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class ApiJsonResponserService {

    /**
     * This function returns JSON response
     * 
     * @param int $httpStatus
     * @param mixed $data
     * @param string|null $message
     * @return Illuminate\HTTP\JsonResponse
     */
    public static function sendData (int $httpStatus, string $message, $data = []): JsonResponse{
        switch(true) {
            case ($data instanceof LengthAwarePaginator) :
                return self::sendPaginatedResponse($httpStatus, $message, $data);
                break;
            default:
                return self::sendUnPaginatedResponse($httpStatus, $message, $data);
        }
    }


    /**
     * This function returns JSON response with meta for paginated data
     * 
     * @param int $httpStatus
     * @param Illuminate\Pagination\LengthAwarePaginator $data
     * @param string|null $message
     * @return Illuminate\HTTP\JsonResponse
     */
    public static function sendPaginatedResponse(int $httpStatus, string $message = null, LengthAwarePaginator $data) : JsonResponse{

        return response()->json([
                                'message' => ucfirst($message),
                                'meta' => collect($data)->except('data'),
                                'status' => $httpStatus,
                                'data' => $data->toArray()['data']
                                ], $httpStatus);
    }

     /**
     * This function returns JSON response with meta for unpaginated data
     * 
     * @param int $httpStatus
     * @param mixed $data
     * @param string|null $message
     * @return Illuminate\HTTP\JsonResponse
     */
    public static function sendUnpaginatedResponse(int $httpStatus, string $message = null, $data) : JsonResponse{

        return response()->json([
                                'message' => ucfirst($message),
                                'status' => $httpStatus,
                                'data' => $data
                                ], $httpStatus);
    }


}