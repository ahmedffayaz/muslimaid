<?php

namespace App\Http\Controllers\Frontend;

use Exception;
use Carbon\Carbon;
use App\Models\Store;
use App\Models\StoreReview;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class StoreReviewController extends Controller
{
    public function show(Request $request, $id)
    {
        $limit = 5;
        $reviewsCount = $request->reviewsCount;
        $reviews = Store::where('id', $id)->first()->reviews()->where('status', 'active')
            ->with('user', function ($query) {
                $query->select('id', 'first_name', 'last_name', 'avatar');
            })->skip($reviewsCount)->take($limit)->get();

        $reviewsCount = $reviews->count();
        $storeReviews = '';
        $stars = '';

        foreach ($reviews as $review) {
            $avatar = $review->user->avatar != null && $review->user->avatar != '' ? asset('frontend/images/avatars/' . $review->user->avatar) : asset('admin-dashboard/images/avatar.png');

            foreach (range(1, 5) as $index) {
                $activeStars = $index <= $review->rating ? 'rating__star--active' : '';
                $starsImage = asset('frontend/images/sprite.svg');
                $stars .= '<svg class="rating__star ' . $activeStars . '" width="13px" height="12px">
                    <g class="rating__fill">
                        <use xlink:href="' . $starsImage . '#star-normal"></use>
                    </g>
                    <g class="rating__stroke">
                        <use xlink:href="' . $starsImage . '#star-normal-stroke"></use>
                    </g>
                </svg>
                <div class="rating__star rating__star--only-edge ' . $activeStars . '">
                    <div class="rating__fill">
                        <div class="fake-svg-icon"></div>
                    </div>
                    <div class="rating__stroke">
                        <div class="fake-svg-icon"></div>
                    </div>
                </div>';
            }

            $storeReviews .= '<li class="reviews-list__item">
                <div class="review">
                    <div class="review__avatar"><img src="' . $avatar . '" alt=""></div>
                    <div class="review__content">
                        <div class="review__author">' . $review->user->first_name . ' ' . $review->user->last_name . ' <span class="review__date">' . Carbon::parse($review->created_at)->isoFormat('DD MMMM, YYYY') . '</span></div>
                        <div class="review__rating">
                            <div class="rating">
                                <div class="rating__body">'
                . $stars .
                '</div>
                            </div>
                        </div>
                        <div class="review__text">' . $review->review . '</div>
                    </div>
                </div>
            </li>';
            $stars = '';
        }
        return response()->json([$storeReviews, $reviewsCount]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'store_id' => 'required|integer',
            'rating' => 'required|integer',
            'review' => 'nullable|max:256'
        ]);
        try {

            DB::beginTransaction();

            $reviews = new StoreReview;
            $reviews->store_id = $request->store_id;
            $reviews->user_id = auth()->user()->id;
            $reviews->review = htmlentities($request->review);
            $reviews->rating = $request->rating;
            $reviews->status = 'pending';
            $reviews->save();

            DB::commit();

            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'success' => 'Thank you for your feedback. The review will appear shortly.'
            ], JsonResponse::HTTP_OK);
        } catch (ValidationException $exception) {
            DB::rollBack();

            return response()->json([
                'status' => JsonResponse::HTTP_NOT_FOUND,
                'error' => $exception->getMessage()
            ], JsonResponse::HTTP_NOT_FOUND);
        } catch (Exception $exception) {
            DB::rollBack();

            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'error' => 'Something went wrong.'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
