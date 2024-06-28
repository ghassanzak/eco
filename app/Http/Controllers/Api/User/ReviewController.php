<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stevebauman\Purify\Facades\Purify;

class ReviewController extends Controller
{
    public function show_reviews(Request $request)
    {

        $reviews = ProductReview::query();

        if (isset($request->product_id) && $request->product_id != '') {
            $reviews = $reviews->where('product_id', $request->product_id);
        } else {
            $products_id = auth()->user()->products->pluck('id')->toArray();
            $reviews = $reviews->whereIn('product_id', $products_id);
        }
        $reviews = $reviews->orderBy('id', 'desc');
        $reviews = $reviews->paginate(10);

        return view('frontend.users.reviews', compact('reviews'));
    }

    public function edit_review($review_id)
    {
        $review = ProductReview::where('id',$review_id)->whereHas('product', function ($query) {
            $query->where('products.user_id', auth()->id());
        })->first();

        if ($review) {
            return view('frontend.users.edit_review', compact('review'));
        } else {
            return redirect()->back()->with([
                'message' => 'Something was wrong',
                'alert-type' => 'danger',
            ]);
        }

    }

    public function update_review(Request $request, $review_id)
    {
        $validator = Validator::make($request->all(), [
            'status'                    => 'required|numeric|min:0|max:1',
            'product_review_details'    => 'required|string',
        ]);
        if($validator->fails()) {return response()->json(['errors' => true, 'messages' => $validator->errors()]);}

        $review = ProductReview::where('id',$review_id)->whereHas('product', function ($query) {
            $query->where('products.user_id', auth()->id());
        })->first();

        if ($review) {
            $data['status']                 = $request->status;
            $data['product_review_details'] = Purify::clean($request->review);

            $review->update($data);

            return redirect()->back()->with([
                'message' => 'review updated successfully',
                'alert-type' => 'success',
            ]);

        } else {
            return redirect()->back()->with([
                'message' => 'Something was wrong',
                'alert-type' => 'danger',
            ]);
        }

    }

    public function destroy_review($review_id)
    {
        $review = ProductReview::where('id',$review_id)->whereHas('product', function ($query) {
            $query->where('products.user_id', auth()->id());
        })->first();

        if ($review) {
            $review->delete();

            return redirect()->back()->with([
                'message' => 'review deleted successfully',
                'alert-type' => 'success',
            ]);

        } else {
            return redirect()->back()->with([
                'message' => 'Something was wrong',
                'alert-type' => 'danger',
            ]);
        }
    }

}
