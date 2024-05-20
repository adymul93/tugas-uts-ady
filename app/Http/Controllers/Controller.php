<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Success response mthod
     * 
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($data, $message, $code = 200)
    {
        $response = [
            'code' => $code,
            'data' => $data,
            'message' => $message,
        ];
        return response()->json($response, $code);
    }

    /**
     * Success response mthod
     * 
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 400)
    {
        $response = [
            'code' => $code,
            'message' => $error,
        ];
        if (!empty($errorMessages)) {
            $response['errors'] = $errorMessages;
        }
        return response()->json($response, $code);
    }
}
