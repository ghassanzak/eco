<?php

namespace App;

use Tymon\JWTAuth\Facades\JWTAuth;

trait MyClass
{
    public function filter($request) {
        $keyword = (isset($request->keyword) && $request->keyword != '') ? $request->keyword : null;
        $status = (isset($request->status) && $request->status != '') ? $request->status : null;
        $sort_by = (isset($request->sort_by) && $request->sort_by != '') ? $request->sort_by : 'id';
        $order_by = (isset($request->order_by) && $request->order_by != '') ? $request->order_by : 'desc';
        $limit_by = (isset($request->limit_by) && $request->limit_by != '') ? $request->limit_by : '10';
    }
    public function refreshToken() {
        $tokenOld = JWTAuth::getToken();
        if (!$token = auth('api')->refresh()) {
            JWTAuth::invalidate($tokenOld);
            $this->returnError('Unauthorized',200);
        }
        return $this->returnData('data',$this->respondWithToken($token),auth()->user()->is_admin==1?'Type User(admin) - Refresh Token':'Type User(not admin) - Refresh Token');
    }
    function respondWithToken($token) {
        return[
            'access_token'=>$token,
            'expire_in' =>auth('api')->factory()->getTTL()*3600*70,
        ];
    }
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
