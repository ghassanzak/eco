<?php
namespace App\Helper;
class MyClass
{
    function Json(Bool $error,string $msg,int $numError) {
        return response()->json(['error'=> $error, 'message' => $msg],$numError);
    }
}