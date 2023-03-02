<?php

namespace App\Traits;

trait HttpResponses
{
    protected  function  sucess($data,string $message=null,int $code=200){
        return response()->json([
            'status' => 'Request Was succesful',
            'messae' => $message,
            'data'   => $data

        ],$code);
    }

    protected  function  error($data,string $message=null,int $code=200){
        return response()->json([
            'status' => 'Request has occurred...',
            'messae' => $message,
            'data'   => $data

        ],$code);
    }
}
