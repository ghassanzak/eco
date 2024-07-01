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
        
        $category = CategoryResource::collection($category);
        return $this->returnData('categorise',$category);
    }

    public function show(IdRequest $request)
    {
        $category = Category::where('id',$request->id)->get();
        $category = CategoryResource::collection($category);
        return $this->returnData('categorise',$category);
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
            return $this->returnSuccess('Ccategory created successfully');
        } else {
            return $this->returnError('Something was wrong',200);
        }
    }

    public function update(CategoryUpRequest $request)
    {
        $category = Category::find($request->id);
        if (!$category) {
            return $this->returnError('id not found',404);
        }
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
        return $this->returnSuccess('Category Successfully Updated');
    }
    public function destroy(IdRequest $request){
        $category = Category::find($request->id);
        if (!$category) {
            return $this->returnError('id not found',404);
        }
        if (file_exists($category->image)) {
            unlink($category->image);
        }
        $category->delete();
        return $this->returnSuccess('category Successfully Deleted');
    }
}
