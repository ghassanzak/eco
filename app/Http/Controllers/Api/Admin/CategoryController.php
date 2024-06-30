<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\CategoryUpRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\General\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $category = Category::get();
        return  CategoryResource::collection($category);
    }

    public function show(IdRequest $request)
    {
        $category = Category::where('id',$request->id)->get();
        return CategoryResource::collection($category);
    }

    public function store(CategoryRequest $request)
    {

        $category = new Category();
        $category->name = $request->name;
        $category->status = $request->status;
        $category->note = $request->note;
        if ($request->image) {
            $category->image = $this->Icon($request->file('image'));
        }
        $category->save();
        if ($category ) {
            return response()->json(['error'=> false, 'message' => 'Ccategory created successfully']);
        } else {
            return response()->json(['error'=> true, 'message' => 'worning']);
        }
    }

    public function update(CategoryUpRequest $request)
    {
        $category = Category::find($request->id);
        $category->name     = $request->name;
        $category->status   = $request->status;
        $category->note     = $request->note;
        if($request->updateImage){
            $category->image = $this->Icon($request->updateImage,$request->id);
        }
        if($request->is_popular){
            $category->is_popular = $request->is_popular;
        }
        $category->save();
        return response()->json(['error'=> false, 'message' => 'Category Successfully Updated']);

    }
    public function destroy(IdRequest $request){
        $category = Category::find($request->id);
        if (file_exists($category->image)) {
            unlink($category->image);
        }
        $category->delete();
        return response()->json(['error'=> false, 'message' => 'category Successfully Deleted']);
    }


    public function Icon($image,$id=null)
    {
        if (isset($image) && ($image != '') && ($image != null)) {
            // $ext = explode('/', mime_content_type($image))[1];
            // $filename = "category_icons-" . time() . rand(1000, 9999) . '.' . $ext;
            $filename = "category_icons-" . time().'-'.'.'.$image->getClientOriginalExtension();
            $path = public_path('assets/category/');
            $db_media_img_path = 'assets/category/' . $filename;
            
            if (!file_exists($path)) {
                mkdir($path, 666, true);
            }
            if ($id != null) {
                $imageOld = Category::find($id)->image;
                if($imageOld)
                if (file_exists($imageOld)) {
                    unlink($imageOld);
                }
            }
            $image->move($path, $filename);
            return isset($db_media_img_path)?$db_media_img_path:null;
        }
    }
}
