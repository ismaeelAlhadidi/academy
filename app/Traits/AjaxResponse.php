<?php 

namespace App\Traits;

trait AjaxResponse {
    function getResponse($status,$msg,$data) {
        $response = [
            'status' => $status,
            'msg' => $msg,
            'data' => $data
        ];
        return response()->json($response);
    }
}