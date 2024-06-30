<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\CategoryUpRequest;
use App\Http\Requests\IdRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public string $file_media = "assets/category/";

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
            $category->image = $this->Image($request->file('image'),$this->file_media);
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
            $this->removeImage($category->image);
            $category->image =  $this->Image($request->file('updateImage'),$this->file_media);
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
}
