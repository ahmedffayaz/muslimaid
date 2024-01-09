<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Store;
use App\Models\StoreReview;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class StoreReviewsController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:view reviews', ['only' => ['index']]);
        $this->middleware('permission:edit reviews', ['only' => ['edit', 'show', 'update']]);
        $this->middleware('permission:add reviews', ['only' => ['create', 'Store']]);
        $this->middleware('permission:delete reviews', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $route = 'index';
        $stores = Store::select('id', 'name', 'slug', 'created_at')->latest()->get();
        $users = User::select('id', 'first_name', 'last_name', 'status', 'created_at')->get();
        $reviews = StoreReview::orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")->orderBy('status', 'desc')->whereHas('store')->with(['store' => function ($query) {
            $query->select('id', 'name', 'slug', 'status', 'created_at')->with('network');
        }])->latest()->paginate(30);
        return view('admin-dashboard.store_reviews.index', compact('reviews', 'stores', 'users', 'route'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::select('id', 'first_name', 'last_name', 'status', 'created_at')->where('status', 1)->get();
        $stores = Store::select('id', 'name', 'slug', 'status', 'created_at')->where('status', 'active')->latest()->get();
        return view('admin-dashboard.store_reviews.create', compact('stores', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'store_id' => 'required',
            'review' => 'required',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return array(
                    'message' => $validator->errors()->first(),
                    'success' => false
                );
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $review = StoreReview::create([
                'user_id' => $request->input('user_id'),
                'store_id' => $request->input('store_id'),
                'review' => $request->input('review'),
            ]);

            // Get average rating against active reviews
            $averageRating = $review->where('store_id', $review->store_id)->where('status', 'active')->avg('rating');

            // Update store rating
            $review->store()->update([
                'rating' => $averageRating
            ]);
            if ($request->ajax()) {
                return array(
                    'message' => 'Review added successfully.',
                    'success' => true
                );
            }
            flash()->success('Review added successfully.');
            return redirect()->route(getAdminPrefix() . '.reviews.index');
        } catch (\Throwable $th) {
            flash()->error('something went wrong! unable to add the Review');
            return redirect()->route(getAdminPrefix() . '.reviews.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit(StoreReview $review)
    {
        $users = User::select('id', 'first_name', 'last_name', 'status', 'created_at')->get();
        $stores = Store::select('id', 'name', 'slug', 'status', 'created_at')->latest()->get();
        return view('admin-dashboard.store_reviews.edit', compact('stores', 'review', 'users'))->render();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StoreReview $review)
    {

        try {
            $review->update($request->all());

            // Get average rating against active reviews
            $averageRating = StoreReview::where('store_id', $review->store_id)->where('status', 'active')->avg('rating');

            // Update store rating
            if ($request->status == 'active') {
                $store = $review->store()->update([
                    'rating' => $averageRating
                ]);
            }

            if (!$request->ajax()) {
                flash()->success('Review updated successfully.');
                return redirect()->back();
            } else {
                return array(
                    'message' => 'Review updated successfully.',
                    'success' => true
                );
            }
        } catch (\Throwable $th) {
            if ($request->ajax()) {
                return array(
                    'message' => 'Something went wrong! unable to update the review',
                    'success' => false,
                );
            }
            flash()->error('Something went wrong! unable to update the review');
            return redirect()->route(getAdminPrefix() . '.reviews.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(StoreReview $review)
    {
        $review->delete();
        flash()->success('Review deleted successfully');
        return redirect()->back();
    }

    function fetch(Request $request)
    {
        if ($request->ajax()) {
            $route = 'index';
            $reviews = StoreReview::orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")->orderBy('status', 'desc')->whereHas('store')->with(['store' => function ($query) {
                $query->select('id', 'slug', 'name', 'status', 'created_at')->with('network');
            }])->latest()->paginate(30);

            return view('admin-dashboard.store_reviews.index_data', compact('reviews', 'route'))->render();
        }
    }

    public function searchReviews(Request $request, StoreReview $reviews)
    {
        $reviews = $reviews->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")->orderBy('status', 'desc')->whereHas('store')->with(['store' => function ($query) {
            $query->select('id', 'slug', 'name', 'status', 'created_at')->with('network');
        }])->newQuery();

        // Search by store.
        if ($request->input('store_id')) {
            $reviews->where('store_id', $request->input('store_id'));
        }

        // Search by reviewer.
        if ($request->input('reviewer_id')) {
            $reviews->where('user_id', $request->input('reviewer_id'));
        }

        // Search by store.
        if ($request->input('status') != -1) {
            $reviews->where('status', $request->input('status'));
        }

        $reviews = $reviews->latest()->paginate(30);
        $route = 'search';
        return view('admin-dashboard.store_reviews.index_data', compact('reviews', 'route'))->render();
    }
}
