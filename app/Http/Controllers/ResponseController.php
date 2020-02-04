<?php

namespace App\Http\Controllers;

class ResponseController extends Controller
{
    public function sendResponse($result, $message)
    {
        $response = [ 'success' => true, 'result' => $result, 'message' => $message ];
        
        return response()->json($response, 200);
    }
    
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = ['success' => false, 'data' => $errorMessages, 'message' => $error];
        
        return response()->json($response, $code);
    }
    
    
    
}
