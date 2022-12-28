<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Store;
use App\Models\StoreReview;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StoreReviewsController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:view reviews', ['only' => ['index']]);
         $this->middleware('permission:edit reviews', ['only' => ['edit','show','update']]);
         $this->middleware('permission:add reviews', ['only' => ['create','Store']]);
         $this->middleware('permission:delete reviews', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $route='index';
        $stores = Store::latest()->get();
        $users = User::get();
        $reviews = StoreReview::orderBy('status', 'desc')->latest()->paginate(30);
        return view('admin-dashboard.store_reviews.index',compact('reviews','stores', 'users','route'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::where('status', 1)->get();
        $stores = Store::latest()->get();
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
        try{
            $review = StoreReview::create($request->all());

            // Get average rating against active reviews
            $averageRating = $review->where('store_id', $review->store_id)->where('status', 'active')->avg('rating');

            // Update store rating
            $review->store()->update([
                'rating' => $averageRating
            ]);

            flash()->success('Review added successfully');
            return redirect()->route('admin.reviews.index');

        }catch (\Throwable $th) {
            flash()->error('something went wrong! unable to add the Review');
            return redirect()->route('admin.reviews.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit(StoreReview $review)
    {
        $users = User::get();
        $stores = Store::latest()->get();
        return view('admin-dashboard.store_reviews.edit', compact('stores','review','users'))->render();
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
        try{
            $review->update($request->all());

            // Get average rating against active reviews
            $averageRating = StoreReview::where('store_id', $review->store_id)->where('status', 'active')->avg('rating');

            // Update store rating
            if($request->status != 'pending'){
                $store = $review->store()->update([
                    'rating' => $averageRating
                ]);
            }

            if(!$request->ajax())
            {
                flash()->success('Review updated successfully');
                return redirect()->back(); }
            else{
                return true;
                }
        } catch (\Throwable $th) {
            flash()->error('something went wrong! unable to update the review');
            return redirect()->route('admin.reviews.index');
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
     if($request->ajax())
     {
        $route = 'index';
        $reviews = StoreReview::latest()->paginate(30);

         return view('admin-dashboard.store_reviews.index_data', compact('reviews','route'))->render();
     }
    }
    public function exportCsv(Request $request)
    {
        try {

            $table = Category::latest()->get();
            $filename = "categories.csv";
            $handle = fopen($filename, 'w+');
            fputcsv($handle, array('Name', 'Parent Category','No of Stores', 'Status'));

            foreach($table as $row) {
                fputcsv($handle, array($row->name, $row->parent->name ?? '', count($row->stores), $row->status ? 'active' : 'in-active'));
            }

            fclose($handle);

            $headers = array(
                'Content-Type' => 'text/csv',
            );

            return \Response::download($filename, 'categories.csv', $headers);
        } catch (\Throwable $th) {

            flash()->error('Error while exporting categories');
            return redirect()->route('admin.categories.index');

        }

    }
    public function searchReviews(Request $request, StoreReview $reviews)
    {
        $reviews = $reviews->newQuery();

        // Search by store.
        if ($request->input('store_id')) {
            $reviews->where('store_id', $request->input('store_id'));
        }

        // Search by reviewer.
        if ($request->input('reviewer_id')) {
            $reviews->where('user_id', $request->input('reviewer_id'));

        }

        // Search by store.
        if ($request->input('status')!=-1) {
            $reviews->where('status', $request->input('status'));
        }

        $reviews = $reviews->latest()->paginate(30);
        $route='search';
        return view('admin-dashboard.store_reviews.index_data', compact('reviews','route'))->render();


    }
}
