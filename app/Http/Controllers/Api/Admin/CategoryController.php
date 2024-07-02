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

    public function index(Request $request)
    {
        $keyword = (isset($request->keyword) && $request->keyword != '') ? $request->keyword : null;
        $status = (isset($request->status) && $request->status != '') ? $request->status : null;
        $sort_by = (isset($request->sort_by) && $request->sort_by != '') ? $request->sort_by : 'id';
        $order_by = (isset($request->order_by) && $request->order_by != '') ? $request->order_by : 'desc';
        $limit_by = (isset($request->limit_by) && $request->limit_by != '') ? $request->limit_by : '10';

        $categories = Category::withCount('products');
        if ($keyword != null) {
            $categories = $categories->search($keyword);
        }
        if ($status != null) {
            $categories = $categories->whereStatus($status);
        }
        
        $categories = $categories->orderBy($sort_by, $order_by);
        $categories = $categories->paginate($limit_by);

        if(!$categories->count()>0) return $this->returnData('data',[],'Invalid Category',200);
        $category = CategoryResource::collection($categories);
        return $this->returnData('Categorise',$category,'Index Categorise',200);
    }

    public function show(IdRequest $request)
    {
        $category = Category::where('id',$request->id)->get();
        if(!$category->count()>0) return $this->returnData('data',[],'Invalid Category',200);
        $category = CategoryResource::collection($category);
        return $this->returnData('Categorise',$category,'Show Categorise',200);
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
            return $this->returnSuccess('Ccategory Created Successfully');
        } else {
            return $this->returnError('Something Was Wrong',200);
        }
    }

    public function update(CategoryUpRequest $request)
    {
        $category = Category::find($request->id);
        if (!$category) return $this->returnSuccess('Invalid Category',200);
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
            return $this->returnSuccess('Invalid Category',200);
        }
        if (file_exists($category->image)) {
            unlink($category->image);
        }
        $category->delete();
        return $this->returnSuccess('Category Successfully Deleted');
    }
}
