<?php

namespace App\Http\Controllers\Frontend;

use Exception;
use App\Models\Store;
use App\Models\Voucher;
use App\Models\Category;
use App\Models\StoreReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class StoreController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $store = Store::where('slug', $slug)->first();

        // $vouchers = Voucher::where('store_id', $store->id)->latest()->paginate(5);
        $count = count($store->cashbacks);
        return view('frontend.stores.show',compact('store','count'));
    }

    public function storeLocation(Request $request)
    {
        if ($request->ajax()) {
            $locations = Store::when($request->has('id'), function($query) use ($request) {
                $query->whereHas('categories', function ($query) use ($request) {
                    $query->whereIn('category_id', $request->id);
                });
            })
            ->with('logo', 'storeAddress')->paginate(10);
            return view('frontend.stores.stores',compact('locations'));
        }

        $locations = Store::with('logo','storeAddress')->paginate(10);
        $categories = Category::with(['stores.storeAddress'])->where('parent_id', '=', 0)->orderBy('name', 'ASC')->get();
        $array = array();
        foreach($categories as $category){
             foreach($category->stores as $store){
                $array['des'][] =$store->description;
                foreach ($store->storeAddress as $address) {
                    $array['lat'][] =$address->latitude;
                    $array['long'][] =$address->longitude;
                }
             }
        }

        return view('frontend.stores.location', compact('locations','categories', 'array'));
    }

    public function showReviews(Request $request, $id)
    {
        $limit = $request->limit;
        $reviews = Store::where('id', $id)->first()->reviews()->where('status', 'active')
            ->with('user', function ($query) {
                $query->select('id', 'first_name', 'last_name', 'avatar');
            })->limit($limit)->get();
        return response()->json([$reviews]);
    }

    /**
     * Save store reviews
     */
    public function storeReviews(Request $request)
    {
        try {
            $request->validate([
                'store_id' => 'required|integer',
                'rating' => 'required|integer',
            ]);

            DB::beginTransaction();

            $reviews = new StoreReview;
            $reviews->store_id = $request->store_id;
            $reviews->user_id = auth()->user()->id;
            $reviews->review = $request->review;
            $reviews->rating = $request->rating;
            $reviews->status = 'pending';
            $reviews->save();

            DB::commit();

            Session::flash('success', 'Thank you for your feedback. <br>The review will appear shortly.');
            return redirect()->back();
        } catch (ValidationException $exception) {
            DB::rollBack();

            Session::flash('error', $exception->getMessage());
            return redirect()->back();
        } catch (Exception $exception) {
            DB::rollBack();

            Session::flash('error', 'Something went wrong.');
            return redirect()->back();
        }
    }
}
