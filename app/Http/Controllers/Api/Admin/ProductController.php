<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\General\CategoryResource;
use App\Http\Resources\General\ProductResource;
use App\Http\Resources\TagResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stevebauman\Purify\Facades\Purify;


class ProductController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'keyword'       => 'nullable|string',
            'category_id'   => 'nullable|numeric',
            'tag_id'        => 'nullable|numeric',
            'status'        => 'nullable|numeric|min:0|max:1',
            'is_popular'    => 'nullable|numeric|min:0|max:1',
            'is_trending'   => 'nullable|numeric|min:0|max:1',
            'sort_by'       => 'nullable|string',
            'order_by'      => 'nullable|string',
            'limit_by'      => 'nullable|numeric',
        ]);
        if($validator->fails()) {return response()->json(['errors' => true, 'messages' => $validator->errors()]);}
        
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
        if ($keyword != null) {
            $products = $products->search($keyword);
        }

        if ($categoryId != null) {
            $products = $products->where('category_id',$categoryId);
        }
        
        if ($tagId != null) {
            $products = $products->whereHas('tags', function ($query) use ($tagId) {
                $query->where('id', $tagId);
            });
        }

        if ($status != null) {
            $products = $products->where('status',$status);
        }

        if ($is_popular != null) {
            $products = $products->where('is_popular',$is_popular);
        }

        if ($is_trending != null) {
            $products = $products->where('is_trending',$is_trending);
        }

        $products = $products->orderBy($sort_by, $order_by);
        $products = $products->paginate($limit_by);

        return ProductResource::collection($products);
    }

    public function create()
    {

        $tags = Tag::pluck('id', 'name');
        $categories = Category::orderBy('id', 'desc')->pluck('id', 'name');
        CategoryResource::collection($tags);
        return ProductResource::collection($categories);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'name'                  => 'required|string',
            'code'                  => 'nullable|numeric',
            'brand'                 => 'nullable|string',
            'current_purchase_cost' => 'required',
            'current_sale_price'    => 'required',
            'available_quantity'    => 'required|numeric',
            'description'           => 'required|min:20|string',
            'is_popular'            => 'nullable|numeric|min:0|max:1',
            'is_trending'           => 'nullable|numeric|min:0|max:1',
            'status'                => 'nullable|numeric|min:0|max:1',
            'category_id'           => 'required',
            'images.*'              => 'nullable|mimes:jpg,jpeg,png,gif',
            'tags.*'                => 'required',

        ]);
        if($validator->fails()) {return response()->json(['errors' => true, 'messages' => $validator->errors()]);}

        $data['name']                   = $request->name;
        $data['current_purchase_cost']  = $request->current_purchase_cost;
        $data['current_sale_price']     = $request->current_sale_price;
        $data['available_quantity']     = $request->available_quantity;
        $data['description']            = Purify::clean($request->description);
        $data['category_id']            = $request->category_id;

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
        // return response()->json(['error'=> false, 'message' => auth()->user()],200);
        $product = auth()->user()->products()->create($data);

        if ($request->images && count($request->images) > 0) {
            $this->Image($product,$request->images);
        }

        if (count($request->tags) > 0) {
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
        if ($product ) {
            return response()->json(['error'=> false, 'message' => 'Product created successfully'],200);
        } else {
            return response()->json(['error'=> true, 'message' => 'worning'],200);
        }
    }

    public function show(Request $request)
    {
        $product = Product::with(['images_product', 'category', 'user', 'reviews'])->where('id',$request->id)->first();
        return new ProductResource($product);
    }

    public function edit($id)
    {
        $tags = Tag::pluck( 'id','name');
        $categories = Category::orderBy('id', 'desc')->pluck('id','name');
        $product = Product::with(['images_product'])->where('id',$id)->first();

        TagResource::collection($tags);
        CategoryResource::collection($categories);
        return ProductResource::collection($product);
    }

    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'id'                  => 'required|numeric',
            'name'                  => 'nullable|string',
            'code'                  => 'nullable|numeric',
            'brand'                 => 'nullable|string',
            'current_purchase_cost' => 'nullable',
            'current_sale_price'    => 'nullable',
            'available_quantity'    => 'nullable|numeric',
            'description'           => 'nullable|min:20|string',
            'is_popular'            => 'nullable|numeric|min:0|max:1',
            'is_trending'           => 'nullable|numeric|min:0|max:1',
            'status'                => 'nullable|numeric|min:0|max:1',
            'category_id'           => 'nullable',
            'images.*'              => 'nullable|mimes:jpg,jpeg,png,gif',
            'tags.*'                => 'nullable',

        ]);
        if($validator->fails()) {return response()->json(['errors' => true, 'messages' => $validator->errors()]);}

        $product = Product::where('id',$request->id)->first();

        if ($product) {
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
                $this->Image($product,$request->images);
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

            return response()->json(['error'=> false, 'message' => 'Product updated successfully'],200);
           
        }
        return response()->json(['error'=> true, 'message' => 'Something was wrong'],200);
        
    }

    public function destroy(Request $request)
    {
        $product = Product::where('id',$request->id)->first();
        // return response()->json(['message' => public_path($product->images_product[0]->name)],200);
        if ($product) {
            if ($product->images_product->count() > 0) {
                
                foreach ($product->images_product as $image) {
                    if (file_exists(public_path($image->name) )) {
                        unlink(public_path($image->name));
                    }
                }
            }
            $product->delete();

            return response()->json(['error'=> false, 'message' => 'Product deleted successfully'],200);
        }

        return response()->json(['error'=> true, 'message' => 'Something was wrong'],200);
    }

    public function removeImage(Request $request)
    {

        $media = ProductImage::where('id',$request->id)->first();
        if ($media) {
            if (file_exists(public_path($media->name))) {
                unlink(public_path($media->name));
            }
            $media->delete();
            return true;
        }
        return false;
    }

    public function Image(Product $product,$images)
    {
        if (isset($images) && count($images) > 0) {
            if (!file_exists('assets/products/')) {mkdir('assets/products/', 666, true);}
            $i = 1;
            foreach ($images as $image) {
                if ( $image && ($image != '') && ($image != null)) {
                    $filename = $product->slug . time() . $i . rand(1000, 9999) . '.' .$image->getClientOriginalExtension();
                    $path = public_path('assets/products/');
                    $db_media_img_path = 'assets/products/' . $filename;

                    $ima = $product->images_product()->create(['name'=>$db_media_img_path]);
                    $image->move($path, $filename);
                    $i++;
                }
            }
        }
    }
}
