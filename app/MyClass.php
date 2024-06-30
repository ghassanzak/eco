<?php

namespace App;

trait MyClass
{
    public  function Json(Bool $error,$msg,int $numError = null){
        if ($numError==null) {
            return response()->json(['error' => $error,'message' => $msg]);
        }
        return response()->json(['error' => $error,'message' => $msg],$numError);
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
