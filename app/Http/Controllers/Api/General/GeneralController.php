<?php

namespace App\Http\Controllers\Api\General;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ReviewResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    public function get_product()
    {
        $product = Product::whereHas('category', function($query){
            $query->whereStatus(1);
        })
        ->active()->orderBy('id', 'asc')->paginate(5);
        if($product->count() > 0){
            $product = ProductResource::collection($product);
            return $this->returnData('$product',$product);
        } else {
            return response()->json(['error' => true, 'message'=> 'No product Found'], 201);
        }
    }

    public function get_category()
    {
        $category = Category::whereStatus(1)
        ->active()->orderBy('id', 'desc')->paginate(5);

        if($category->count() > 0){
            $category = CategoryResource::collection($category);
            return $this->returnData('categorise',$category);
        } else {
            return response()->json(['error' => true, 'message'=> 'No category Found'], 201);
        }
    }

    public function show_product($slug)
    {
        $product = Product::with(['category', 'images_product',
            // 'approved_comments' => function($query) {
            //     $query->orderBy('id', 'desc');
            // }
        ]);

        $product = $product->whereHas('category', function ($query) {
                $query->whereStatus(1);
            });

        $product = $product->whereSlug($slug);
        $product = $product->active()->first();

        if($product->count() > 0) {
            return new ProductResource($product);
        } else {
            return response()->json(['error' => true, 'message'=> 'No product Found'], 201);
        }
    }
    public function show_category($id)
    {
        $category = Category::whereStatus(1)->whereId($id)->active()->first();

        if($category->count() > 0) {
            return new CategoryResource($category);
        } else {
            return response()->json(['error' => true, 'message'=> 'No category Found'], 201);
        }
    }

    public function show_review_product($slug, $id)
    {
        $product = Product::with(['category',
            'reviews' => function($query) {
                $query->active()->orderBy('id', 'desc');
            }
        ]);

        $product = $product->whereHas('category', function ($query) {
            $query->active();
        });

        $product = $product->whereSlug($slug);
        $product = $product->active()->first();

        if($product->count() > 0) {
            $reviews = ReviewResource::collection($product->reviews);
            return $this->returnData('$reviews',$reviews);
        } else {
            return response()->json(['error' => true, 'message'=> 'No reviews Found'], 201);
        }
    }
}
