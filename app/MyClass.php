<?php

namespace App;

trait MyClass
{
    public function returnError($msg, $errNum)
    {
        return response()->json([
            'error' => true,
            'msg' => $msg
        ],$errNum);
    }
    public function returnSuccess($msg = "", $sucNum = "200")
    {
        return response()->json([
            'error' => false,
            'msg' => $msg
        ],$sucNum);
    }
    public function returnData($key, $value, $msg = "", $sucNum = "200")
    {
        return response()->json([
            'error' => false,
            'msg' => $msg,
            $key => $value
        ],$sucNum);
    }

    public  function Image($image ,string $path){
        if ( $image && ($image != '') && ($image != null)) {

            $this->createOrExistFile($path);

            $filename = time(). rand(100, 999) . rand(1000, 9999) . '.' .$image->getClientOriginalExtension();
            $public_path = public_path($path);
            $db_media_img_path = $path . $filename;

            $image->move($public_path, $filename);
            
            return $db_media_img_path;
        }
    }
    public function createOrExistFile($path) {
        if (!file_exists($path)) {mkdir($path, 666, true);}
    }
    public function removeImage(string $image = null) {
        if($image && $image != null) if(file_exists($image)) unlink($image);
    }
}
