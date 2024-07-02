<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\IdRequest;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductUpRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\TagResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Tag;
use App\Providers\AppServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stevebauman\Purify\Facades\Purify;


class ProductController extends Controller
{
    public string $file_media = "assets/products/";
    public function index(Request $request)
    {
        $keyword =      (isset($request->keyword)       && $request->keyword != '')     ? $request->keyword     : null;
        $categoryId =   (isset($request->category_id)   && $request->category_id != '') ? $request->category_id : null;
        $tagId =        (isset($request->tag_id)        && $request->tag_id != '')      ? $request->tag_id      : null;
        $status =       (isset($request->status)        && $request->status != '')      ? $request->status      : null;
        $is_popular =   (isset($request->is_popular)    && $request->is_popular != '')  ? $request->is_popular  : null;
        $is_trending =  (isset($request->is_trending)   && $request->is_trending != '') ? $request->is_trending : null;
        $sort_by =      (isset($request->sort_by)       && $request->sort_by != '')     ? $request->sort_by     : 'id';
        $order_by =     (isset($request->order_by)      && $request->order_by != '')    ? $request->order_by    : 'desc';
        $limit_by =     (isset($request->limit_by)      && $request->limit_by != '')    ? $request->limit_by    : '10';

        $products = Product::with(['user', 'category', 'reviews']);
        if($keyword != null )       $products = $products->search($keyword);
        if($categoryId != null )    $products = $products->where('category_id',$categoryId);
        if($tagId != null )         $products = $products->whereHas('tags', function ($query) use ($tagId) { $query->where('id', $tagId);});
        if($status != null )        $products = $products->where('status',$status);
        if($is_popular != null )    $products = $products->where('is_popular',$is_popular);
        if($is_trending != null )   $products = $products->where('is_trending',$is_trending);
        $products = $products->orderBy($sort_by, $order_by);
        $products = $products->paginate($limit_by);
        if(!$products->count()>0) return $this->returnData('data',[],'Invalid Product');
        $products = ProductResource::collection($products);
        return $this->returnData('data',$products,'Index Product');
    }

    public function create()
    {

        // $tags = Tag::pluck('id', 'name');
        // $categories = Category::orderBy('id', 'desc')->pluck('id', 'name');
        // CategoryResource::collection($tags);
        // return ProductResource::collection($categories);
    }

    public function store(ProductRequest $request)
    {
        $data['name']                   = $request->name;
        $data['current_purchase_cost']  = $request->current_purchase_cost;
        $data['current_sale_price']     = $request->current_sale_price;
        $data['available_quantity']     = $request->available_quantity;
        $data['description']            = Purify::clean($request->description);
        $category = Category::find($request->category_id);
        if ($category) {
            $data['category_id']        = $request->category_id;
        }else{
            return $this->returnError('Invalid Category',404);
        }

        if (isset($request->code) && $request->code != null && $request->code != '') {
            $data['code']  = $request->code;
        }
        if (isset($request->brand) && $request->brand != null && $request->brand != '') {
            $data['brand']  = $request->brand;
        }
        if (isset($request->is_popular) && $request->is_popular != null && $request->is_popular != '') {
            $data['is_popular']  = $request->is_popular;
        }
        if (isset($request->is_trending) && $request->is_trending != null && $request->is_trending != '') {
            $data['is_trending']  = $request->is_trending;
        }
        if (isset($request->status) && $request->status != null && $request->status != '') {
            $data['status']  = $request->status;
        }
        // return $this->returnSuccess(auth()->user(),200);
        $product = auth()->user()->products()->create($data);

        if ($request->images && count($request->images) > 0) {
            foreach ($request->images as $key => $image) {
                $product->images_product()->create(['name'=>$this->Image($image,$this->file_media)]);
            }
        }

        if (isset($request->tags) && count($request->tags) > 0) {
            $new_tags = [];
            foreach ($request->tags as $tag) {
                $tag = Tag::firstOrCreate([
                    'id' => $tag
                ], [
                    'name' => $tag
                ]);

                $new_tags[] = $tag->id;
            }
            $product->tags()->sync($new_tags);
        }
        if ($product) {
            return $this->returnSuccess('Product created successfully',200);
        } else {
            return $this->returnError('Something Was Wrong',200);
        }
    }

    public function show(IdRequest $request)
    {
        $product = Product::with(['images_product', 'category', 'user', 'reviews'])->where('id',$request->id)->first();
        if(!$product) return $this->returnData('data',[],'Invalid Product',200);
        $product = new ProductResource($product);
        return $this->returnData('data',$product,'Show Product',200);
    }
    public function edit($id)
    {
        // $tags = Tag::pluck( 'id','name');
        // $categories = Category::orderBy('id', 'desc')->pluck('id','name');
        // $product = Product::with(['images_product'])->where('id',$id)->first();

        // // TagResource::collection($tags);
        // // CategoryResource::collection($categories);
        // $product = new ProductResource($product);
        // return $this->returnData('product',$product);

    }
    public function update(ProductUpRequest $request)
    {
        $product = Product::where('id',$request->id)->first();
        if(!$product) return $this->returnError('Invalid Product',404);
        
        if (isset($request->name) && $request->name != null && $request->name != '') {
            $data['name']  = $request->name;
        }
        if (isset($request->current_purchase_cost) && $request->current_purchase_cost != null && $request->current_purchase_cost != '') {
            $data['current_purchase_cost']  = $request->current_purchase_cost;
        } 
        if (isset($request->current_sale_price) && $request->current_sale_price != null && $request->current_sale_price != '') {
            $data['current_sale_price']  = $request->current_sale_price;
        } 
        if (isset($request->available_quantity) && $request->available_quantity != null && $request->available_quantity != '') {
            $data['available_quantity']  = $request->available_quantity;
        } 
        if (isset($request->description) && $request->description != null && $request->description != '') {
            $data['description']  = Purify::clean($request->description);
        } 
        if (isset($request->category_id) && $request->category_id != null && $request->category_id != '') {
            $data['category_id']  = $request->category_id;
        }
        if (isset($request->code) && $request->code != null && $request->code != '') {
            $data['code']  = $request->code;
        }
        if (isset($request->brand) && $request->brand != null && $request->brand != '') {
            $data['brand']  = $request->brand;
        }
        if (isset($request->is_popular) && $request->is_popular != null && $request->is_popular != '') {
            $data['is_popular']  = $request->is_popular;
        }
        if (isset($request->is_trending) && $request->is_trending != null && $request->is_trending != '') {
            $data['is_trending']  = $request->is_trending;
        }
        if (isset($request->status) && $request->status != null && $request->status != '') {
            $data['status']  = $request->status;
        }

        if(isset($data))
        $product->update($data);

        if ($request->images && count($request->images) > 0) {
            foreach ($request->images as $key => $image) {
                $product->images_product()->create(['name'=>$this->Image($image,$this->file_media)]);
            }
        }

        if (isset($request->tags)&&count($request->tags) > 0) {
            $new_tags = [];
            foreach ($request->tags as $tag) {
                $tag = Tag::firstOrCreate([
                    'id' => $tag
                ], [
                    'name' => $tag
                ]);

                $new_tags[] = $tag->id;
            }
            $product->tags()->sync($new_tags);
        }

        return $this->returnSuccess('Product Updated Successfully',200);
    }

    public function destroy(IdRequest $request)
    {
        $product = Product::where('id',$request->id)->first();
        if ($product) {
            $product->delete();
            return $this->returnSuccess('Product Deleted Successfully',200);
        }
        return $this->returnError('Invalid Product',404);
    }

    public function removeImageForProduct(IdRequest $request)
    {
        $media = ProductImage::where('id',$request->id)->first();
        if ($media) {
            $this->removeImage($media->name);
            $media->delete();
            return 'true';
        }
        return 'false';
    }
}
