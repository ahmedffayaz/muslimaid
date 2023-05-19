<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Page;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\StoreResource;
use App\Http\Resources\StoreDetailResource;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Illuminate\Support\Facades\Validator;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $page = Page::whereSlug('stores')->whereType('system')->whereStatus('active')->pluck('banner_image')->firstOrFail();

            $stores = Store::when($request->has('letter'), function ($query) use ($request) {
                $query->where('name', 'like', $request->input('letter') . '%');
            })->when($request->orderBy == 'latest', function ($query) {
                $query->latest();
            })->when($request->orderBy == 'popularity', function ($query) {
                $query->withCount('clicks')->orderByDesc('clicks_count');
            })->when($request->orderBy == 'cashback-amount', function ($query) {
                $query->whereHas('cashbacks', function ($query) {
                    $query->whereType('fixed');
                })->get()->filter(function ($query) {
                    $cashback = $query->getCashback();
                    return (strpos($cashback, '£') !== false);
                });
            })->when($request->orderBy == 'cashback-percentage', function ($query) {
                $query->whereHas('cashbacks', function ($query) {
                    $query->where('type', 'percentage');
                })->get()->filter(function ($query) {
                    $cashback = $query->getCashback();
                    return (strpos($cashback, '%') !== false);
                });
            })->when($request->tag, function ($query) use ($request) {
                $query->whereHas('tags', function ($query) use ($request) {
                    $query->where('title', $request->input('tag'));
                });
            })->whereStatus('active')->paginate(20)->appends(request()->input());

            if ($stores->count() == 0) {
                $data = [
                    'status' => 200,
                    'message' => 'No store found',
                    'data' => []
                ];
                return response()->json($data, 200);
            }

            $data = [
                'status' => 200,
                'message' => 'Success',
                'data' => [
                    'main_banner_image' => getBannerImageUrl($page),
                    'stores' => StoreResource::collection($stores),
                    'meta_data' => [
                        "next" => $stores->nextPageUrl(),
                        "previous" => $stores->previousPageUrl(),
                        "per_page" => 20,
                        "total" => $stores->total(),
                        "current_page" => $stores->currentPage(),
                        "total_pages" => $stores->lastPage(),
                        "first" => $stores->firstItem(),
                        "last" => $stores->lastItem()
                    ]
                ]
            ];
            return response()->json($data, 200);
        } catch (ModelNotFoundException $e) {
            $data = [
                'status' => 404,
                'message' => 'Something went wrong, try again.',
                'data' => []
            ];
            return response()->json($data, 404);
        } catch (Exception $e) {
            $data = [
                'status' => 500,
                'message' => 'Something went wrong, try again.',
                'data' => []
            ];
            return response()->json($data, 500);
        }
    }

    public function affrobotStores(Request $request)
    {
        try {
            $page = Page::whereSlug('stores')->whereType('system')->whereStatus('active')->pluck('banner_image')->firstOrFail();
            $keywords = explode(",", $request->keywords);
            if (!empty($keywords[0])) {
                $stores = Store::when($request->keywords, function ($query) use ($keywords) {
                    $query->whereHas('storeRuleData', function ($query) use ($keywords) {
                        $query->where('key', 'meta:keywords');
                        foreach ($keywords as $keyword) {
                            $keyword = trim($keyword);
                            $query->orWhere('value', 'like', '%' . $keyword . '%');
                        }
                    });
                })->whereStatus('active')->paginate(20)->appends(request()->input());
                $data = [
                    'status' => 200,
                    'message' => 'Success',
                    'data' => [
                        'main_banner_image' => getBannerImageUrl($page),
                        'stores' => StoreResource::collection($stores)
                    ]
                ];
                return response()->json($data, 200);
            } else {
                $data = [
                    'status' => 200,
                    'message' => 'No store found',
                    'data' => []
                ];
                return response()->json($data, 200);
            }
        } catch (ModelNotFoundException $e) {
            $data = [
                'status' => 404,
                'message' => 'Something went wrong, try again.',
                'data' => []
            ];
            return response()->json($data, 404);
        } catch (Exception $e) {
            $data = [
                'status' => 500,
                'message' => 'Something went wrong, try again.',
                'data' => []
            ];
            return response()->json($data, 500);
        }
    }

    public function competitorStores(Request $request)
    {
        try {
            if ($request->links) {
                $links = $request->links;
                $query = Store::orderBy('id');
                foreach ($links as $link) {
                    $query->orWhere('competitors', 'LIKE', '%'.$link.'%');
                }
                $stores = $query->whereStatus('active')->paginate(12);
                $data = [
                    'status' => 200,
                    'message' => 'Success',
                    'data' => [
                        'stores' => StoreResource::collection($stores)
                    ]
                ];
                return response()->json($data, 200);
            } else {
                $data = [
                    'status' => 200,
                    'message' => 'No store found',
                    'data' => []
                ];
                return response()->json($data, 200);
            }
        } catch (ModelNotFoundException $e) {
            $data = [
                'status' => 404,
                'message' => $e->getMessage(),
                'data' => []
            ];
            return response()->json($data, 404);
        } catch (Exception $e) {
            $data = [
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ];
            return response()->json($data, 500);
        }
    }

    public function favoriteStores()
    {
        try {
            $cashblackStoreIds = Store::whereHas('categories', function ($query) {
                $query->where('slug', 'cashblack-to-your-door');
            })->pluck('id');
            $favoriteStores = auth()->user()->favoriteStores()
                ->whereNotIn('stores.id', $cashblackStoreIds)
                ->paginate(20)->appends(request()->input());

            $response = [
                'status' => 200,
                'message' => 'Success',
                'data' => [
                    'favorite_stores' => StoreResource::collection($favoriteStores),
                    'meta_data' => [
                        "next" => $favoriteStores->nextPageUrl(),
                        "previous" => $favoriteStores->previousPageUrl(),
                        "per_page" => 20,
                        "total" => $favoriteStores->total(),
                        "current_page" => $favoriteStores->currentPage(),
                        "total_pages" => $favoriteStores->lastPage(),
                        "first" => $favoriteStores->firstItem(),
                        "last" => $favoriteStores->lastItem()
                    ]
                ]
            ];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $data = [
                'status' => 500,
                'message' => 'Something went wrong, try again.',
                'data' => []
            ];
            return response()->json($data, 500);
        }
    }

    public function favoriteCashbackStores()
    {
        try {
            $favoriteStores = auth()->user()->favoriteStores()->whereStatus('active')->whereHas('categories', function ($query) {
                $query->where('slug', 'cashblack-to-your-door');
            })->paginate(20)->appends(request()->input());

            $response = [
                'status' => 200,
                'message' => 'Success',
                'data' => [
                    'favorite_stores' => StoreResource::collection($favoriteStores),
                    'meta_data' => [
                        "next" => $favoriteStores->nextPageUrl(),
                        "previous" => $favoriteStores->previousPageUrl(),
                        "per_page" => 20,
                        "total" => $favoriteStores->total(),
                        "current_page" => $favoriteStores->currentPage(),
                        "total_pages" => $favoriteStores->lastPage(),
                        "first" => $favoriteStores->firstItem(),
                        "last" => $favoriteStores->lastItem()
                    ]
                ]
            ];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $data = [
                'status' => 500,
                'message' => 'Something went wrong, try again.',
                'data' => []
            ];
            return response()->json($data, 500);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addFavoriteStores(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'storeId' => ['required'],
            ]);

            if ($validator->fails()) {
                $data = [
                    'status' => 406,
                    'message' => $validator->errors()->first(),
                    'data' => []
                ];
                return response()->json($data, 406);
            }
            $store = Store::find($request->storeId);
            if ($store) {
                auth()->user()->favoriteStores()->syncWithoutDetaching([$store->id]);
            }
            $data = [
                'status' => 200,
                'message' => 'Store added to favorite list successfully.',
                'data' => ['store' => new StoreDetailResource($store)]
            ];
            return response()->json($data, 200);
        } catch (Exception $ex) { // Anything that went wrong
            $data = [
                'status' => 500,
                'message' => 'Something went wrong, try again.',
                'data' => []
            ];
            return response()->json($data, 500);
        }
    }

    public function removeFavoriteStores(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'storeId' => ['required'],
            ]);

            if ($validator->fails()) {
                $data = [
                    'status' => 406,
                    'message' => $validator->errors()->first(),
                    'data' => []
                ];
                return response()->json($data, 406);
            }
            $store = Store::find($request->storeId);
            if ($store) {
                auth()->user()->favoriteStores()->detach($store->id);
            }
            $data = [
                'status' => 200,
                'message' => 'Store removed from favorite list successfully.',
                'data' => []
            ];
            return response()->json($data, 200);
        } catch (Exception $ex) { // Anything that went wrong
            $data = [
                'status' => 500,
                'message' => 'Something went wrong, try again.',
                'data' => []
            ];
            return response()->json($data, 500);
        }
    }


    public function show($slug)
    {
        try {
            $store = Store::where('slug', $slug)->whereStatus('active')->firstOrFail();
            $data = [
                'status' => 200,
                'message' => 'Success',
                'data' => ['store' => new StoreDetailResource($store)]
            ];
            return response()->json($data, 200);
        } catch (ModelNotFoundException $ex) { // Store not found
            $data = [
                'status' => 404,
                'message' => 'Store not found',
                'data' => []
            ];
            return response()->json($data, 404);
        } catch (Exception $ex) { // Anything that went wrong
            $data = [
                'status' => 500,
                'message' => 'Something went wrong, try again.',
                'data' => []
            ];
            return response()->json($data, 500);
        }
    }

    public function vouchers(Request $request)
    {
        $stores = Store::has('vouchers')->select('stores.*');
        if ($request->get('search')) {
            $stores = $stores->where('name', 'like', '%' . $request->get('search') . '%');
        }
        if ($request->get('name_sort')) {
            $order = $request->get('name_sort') == 'descending' ? 'desc' : 'asc';
            $stores = $stores->orderBy('name', $order);
        } else {
            $stores = $stores->orderBy('id', 'DESC');
        }
        $limit = $request->has('per_page') ? $request->get('per_page') : 10;
        $stores = $stores->paginate($limit)->appends(request()->input());
        $stores->appends(
            [
                'search'   => $request->get('search'),
                'per_page'  => $limit,
                'name_sort' => $request->get('name_sort')
            ]
        );
        return StoreResource::collection($stores);
    }
    
    public function getCashbackStore(Request $request){
       
        try {
            $validator = Validator::make($request->all(), [
                'store_id' => 'required',
                'cashback_type' => 'required',
                'cashback_id' => 'required'
            ]);
            if ($validator->fails()) {
                $data = [
                    'status' => 406,
                    'message' => $validator->errors()->first(),
                    'data' => []
                ];
                return response()->json($data, 406);
            }
            $deeplinkUrl = '';
            $storeId = $request->input('store_id');
           
            $store = Store::findOrFail($storeId);
            // Override network's store cashback
            if ($store->override_network && $request->cashback_type == 'bonus_cashback' && !empty($request->input('cashback_id'))) {
                $cashback = $store->cashback->findOrFail($request->input('cashback_id'));
                if (!$cashback->tracking_url) {
                    $networkId = $store->network->id;
                    $trackingUrl = $store->tracking_url;
                    $clickIdentifier = $store->network->click_ref;

                    $deeplinkIdentifier  = optional($store)->deeplink_url ? $store->network->deeplink_identifier : '';
                    $deeplinkUrl = optional($store)->deeplink_url ? $store->deeplink_url : '';
                } else {
                    $networkId = $cashback->network_id;
                    $trackingUrl = $cashback->tracking_url;
                    $clickIdentifier = $cashback->network->click_ref;

                    $deeplinkIdentifier = optional($cashback)->deeplink_url ? $cashback->network->deeplink_identifier : '';
                    $deeplinkUrl = optional($cashback)->deeplink_url ? $cashback->deeplink_url : '';
                }
            } else {
                $networkId = $store->network->id;
                $clickIdentifier = $store->network->click_ref;
                $trackingUrl = $store->tracking_url;

                $deeplinkIdentifier  = optional($store)->deeplink_url ? $store->network->deeplink_identifier : '';
                $deeplinkUrl = $store->deeplink_url;
            }

            // Cashback percentage
            $customCashbackPercentage = $store->custom_cashback_percentage;

            $cashbackPercent = $customCashbackPercentage ? $customCashbackPercentage : SiteSetting::where('type', 'cashback_percentage')->first()->value;

            if (!$cashbackPercent) {
                $cashbackPercent = 0;
            }

            $click = ExitClick::create([
                'store_id' => $storeId,
                'user_id' => auth()->user()->id ?? 1,
                'network_id' => $networkId,
                'exit_url' => '#',
                'current_cashback_percentage' => $cashbackPercent
            ]);

            $click->exit_url = $trackingUrl . $clickIdentifier . $click->id . $deeplinkIdentifier . $deeplinkUrl;
            $click->update();

            $url = encrypt($click->exit_url);

            if ($request->input('voucher_id')) {
                $redeemed = RedeemedVoucher::create([
                    'user_id' => auth()->user()->id ?? 1,
                    'voucher_id' => $request->input('voucher_id'),
                ]);
            }
            $hashStoreId = encrypt($store->id);
           $data = [
                'status' => 200,
                'message' => 'Success',
                'data' => [
                    'url' => route('click.redirect', [$hashStoreId, $url]) . '?cashbackId=' . encrypt($request->cashback_id)
                ]
            ];
            
            return response()->json($data, 200);
        }
        catch (ModelNotFoundException $ex) { // Store not found
            $data = [
                'status' => 404,
                'message' => 'Store not found',
                'data' => []
            ];
            return response()->json($data, 404);
            dd($ex);
        } catch (Exception $ex) { // Anything that went wrong
            $data = [
                'status' => 500,
                'message' => 'Something went wrong, try again.',
                'data' => []
            ];
            return response()->json($data, 500);
        }
    }
}
