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
use Illuminate\Support\Facades\Validator;

class StoreReviewController extends Controller
{
    public function show(Request $request, $id)
    {
        try {
            $limit = 5;
            $reviewsCount = $request->reviewsCount;
            $reviews = Store::where('id', $id)->first()->reviews()->where('status', 'active')
                ->with('user', function ($query) {
                    $query->select('id', 'first_name', 'last_name', 'avatar');
                })->skip($reviewsCount)->take($limit)->get();

            $reviewsCount = $reviews->count();

            return response()->json([
                'reviews' => view('frontend.stores.reviews', compact('reviews'))->render(),
                $reviewsCount
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'error' => 'Something went wrong.'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'store_id' => 'required|integer',
            'rating' => 'required|integer',
            'review' => 'required|max:256'
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
                'error' => $validator->errors()->first(),
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        try {
            DB::beginTransaction();

            StoreReview::create([
                'store_id' => $request->store_id,
                'user_id' => auth()->user()->id,
                'review' => htmlentities($request->review),
                'rating' => $request->rating,
                'status' => 'pending',
            ]);

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
