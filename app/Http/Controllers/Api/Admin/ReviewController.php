<?php

namespace App\Http\Controllers\Api\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\IdRequest;
use App\Http\Requests\ReviewUpRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ReviewResource;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stevebauman\Purify\Facades\Purify;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $keyword = (isset($request->keyword)        && $request->keyword != '')     ? $request->keyword : null;
        $productId = (isset($request->product_id)   && $request->product_id != '')  ? $request->product_id : null;
        $status = (isset($request->status)          && $request->status != '')      ? $request->status : null;
        $sort_by = (isset($request->sort_by)        && $request->sort_by != '')     ? $request->sort_by : 'id';
        $order_by = (isset($request->order_by)      && $request->order_by != '')    ? $request->order_by : 'desc';
        $limit_by = (isset($request->limit_by)      && $request->limit_by != '')    ? $request->limit_by : '10';

        $reviews =          ProductReview::query();
        // $reviews =          $keyword != null     ?? $reviews->search($keyword);
        // $reviews =          $productId != null   ?? $reviews->where('product_id',$productId);
        // $reviews =          $status != null      ?? $reviews->where('status',$status);
        $reviews =          $reviews->orderBy($sort_by, $order_by);
        $reviews =          $reviews->paginate($limit_by);

        if(!$reviews->count()>0) return $this->returnData('data',[],'Invalid Reviews');
        $reviews = ReviewResource::collection($reviews);
        return $this->returnData('data',$reviews,'Index Review');
    }

    public function show(IdRequest $request) {
        $review = ProductReview::find($request)->first();
        if(!$review) return $this->returnData('data',[] ,'Invalid Reviews',200);
        return  $this->returnData('data',new ReviewResource($review) ,'Show Review',200);
    }
    public function update(ReviewUpRequest $request){
        $review = ProductReview::where('id', $request->id)->first();
        if(!$review) return $this->returnError('Invalid Reviews',404);
        
        $data['status']                 = $request->status;
        $data['product_review_details'] = Purify::clean($request->product_review_details);
        $review->update($data);
        return $this->returnSuccess('Review updated successfully',200);
    }
    public function destroy(IdRequest $request)
    {
        $review = ProductReview::where('id', $request->id)->first();
        if(!$review) return $this->returnError('Invalid Reviews',404);
        $review->delete();
        return $this->returnSuccess('Review deleted successfully',200);
    }
}
