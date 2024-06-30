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

        $reviews = ProductReview::query();
        $reviews = $keyword != null     ?? $reviews->search($keyword);
        $reviews = $productId != null   ?? $reviews->where('product_id',$productId);
        $reviews = $status != null      ?? $reviews->where('status',$status);
        $reviews = $reviews->orderBy($sort_by, $order_by);
        $reviews = $reviews->paginate($limit_by);

        return ReviewResource::collection($reviews);
    }
    public function edit(Request $request)
    {
        $review = ProductReview::where('id', $request->id)->first();
        return new ReviewResource($review);
    }
    public function update(ReviewUpRequest $request)
    {
        $review = ProductReview::where('id', $request->id)->first();
        if ($review) {
            $data['status']                 = $request->status;
            $data['product_review_details'] = Purify::clean($request->product_review_details);
            $review->update($data);
            return response()->json(['error'=> false, 'message' => 'Review updated successfully'],200);
        }
        return response()->json(['error'=> true, 'message' => 'Something was wrong'],200);
    }
    public function destroy(IdRequest $request)
    {
        $review = ProductReview::where('id', $request->id)->first();
        if ($review) {
            $review->delete();
            return response()->json(['error'=> false, 'message' => 'Review deleted successfully'],200);
        }
        return response()->json(['error'=> true, 'message' => 'Something was wrong'],200);
    }
}
