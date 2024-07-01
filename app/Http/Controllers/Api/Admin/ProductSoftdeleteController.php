<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\IdRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductSoftdeleteController extends Controller
{
    public function index(Request $request) {
        $keyword =      (isset($request->keyword)       && $request->keyword != '')     ? $request->keyword     : null;
        $categoryId =   (isset($request->category_id)   && $request->category_id != '') ? $request->category_id : null;
        $tagId =        (isset($request->tag_id)        && $request->tag_id != '')      ? $request->tag_id      : null;
        $status =       (isset($request->status)        && $request->status != '')      ? $request->status      : null;
        $is_popular =   (isset($request->is_popular)    && $request->is_popular != '')  ? $request->is_popular  : null;
        $is_trending =  (isset($request->is_trending)   && $request->is_trending != '') ? $request->is_trending : null;
        $sort_by =      (isset($request->sort_by)       && $request->sort_by != '')     ? $request->sort_by     : 'id';
        $order_by =     (isset($request->order_by)      && $request->order_by != '')    ? $request->order_by    : 'desc';
        $limit_by =     (isset($request->limit_by)      && $request->limit_by != '')    ? $request->limit_by    : '10';

        $products = Product::onlyTrashed()->with(['user', 'category', 'reviews']);
        if($keyword != null )       $products = $products->search($keyword);
        if($categoryId != null )    $products = $products->where('category_id',$categoryId);
        if($tagId != null )         $products = $products->whereHas('tags', function ($query) use ($tagId) { $query->where('id', $tagId);});
        if($status != null )        $products = $products->where('status',$status);
        if($is_popular != null )    $products = $products->where('is_popular',$is_popular);
        if($is_trending != null )   $products = $products->where('is_trending',$is_trending);
        $products = $products->orderBy($sort_by, $order_by);
        $products = $products->paginate($limit_by);

        if(!$products->count()>0) return $this->returnError('products archive not found',404);

        $products = ProductResource::collection($products);
        return $this->returnData('products', $products);
    }

    public function show(IdRequest $request)
    {
        $product = Product::onlyTrashed()->with(['images_product', 'category', 'user', 'reviews'])->where('id',$request->id)->first();
        if(!$product) return $this->returnError('product archive not found',404);
        $product = new ProductResource($product);
        return $this->returnData('product',$product);
    }

    public function restore(IdRequest $request)
    {
        $product = Product::withTrashed()->where('id',$request->id);
        if ($product) {
            $product->restore();
            return $this->returnSuccess('Product restore archive successfully',200);
           
        }
        return $this->returnError('Something was wrong',200);
        
    }

    public function delete(IdRequest $request)
    {
        $product = Product::where('id',$request->id)->first();
        // return response()->json(['message' => public_path($product->images_product[0]->name)],200);
        if ($product) {
            if ($product->images_product->count() > 0) {
                
                foreach ($product->images_product as $image) {
                    $this->removeImage($image->name);
                }
            }
            $product->forceDelete();

            return $this->returnSuccess('Product deleted archive successfully',200);
        }
        return $this->returnError('product not found',404);
    }
}
