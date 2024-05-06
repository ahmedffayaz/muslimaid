<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Page;
use App\Models\Store;
use App\Models\Voucher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\StoreResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\StoreDetailResource;
use App\Http\Resources\VoucherResource;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

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

            $stores = Store::select('id', 'name', 'slug', 'status', 'created_at')->with(['images', 'logo', 'storeAddress'])->withCount('cashbacks')
                ->when($request->has('letter'), function ($query) use ($request) {
                    if ($request->letter != '0-9') {
                        $query->where('name', 'like', $request->input('letter') . '%');
                    } else {
                        $paramLetter = '0-9';
                        $query->where('name', 'REGEXP', "^[{$paramLetter}]");
                    }
                })->when($request->orderBy == 'latest', function ($query) {
                    $query->latest();
                })->when($request->orderBy == 'popularity', function ($query) {
                    $query->withCount('clicks')->orderByDesc('clicks_count');
                })->when($request->orderBy == 'cashback-amount' || $request->orderBy == 'cashback-percentage' || $request->orderBy == 'cashback-amount-asc' || $request->orderBy == 'cashback-amount-desc' || $request->orderBy == 'cashback-percentage-asc' || $request->orderBy == 'cashback-percentage-desc', function ($query) use ($request) {
                    $query->whereHas('cashback', function ($query) use ($request) {
                        $query->whereNotNull('sale_commission');
                        $query->when($request->orderBy == 'cashback-amount' || $request->orderBy == 'cashback-amount-asc' || $request->orderBy == 'cashback-amount-desc', function ($query) {
                            $query->whereType('fixed');
                        })->when($request->orderBy == 'cashback-percentage' || $request->orderBy == 'cashback-percentage-asc' || $request->orderBy == 'cashback-percentage-desc', function ($query) {
                            $query->whereType('percentage');
                        });
                    })->with(['cashback' => function ($query) use ($request) {
                        $query->whereNotNull('sale_commission')->select(['id', 'store_id', 'sale_commission', 'type']);
                        $query->when($request->orderBy == 'cashback-amount' || $request->orderBy == 'cashback-amount-asc' || $request->orderBy == 'cashback-amount-desc', function ($query) {
                            $query->whereType('fixed');
                        })->when($request->orderBy == 'cashback-percentage' || $request->orderBy == 'cashback-percentage-asc' || $request->orderBy == 'cashback-percentage-desc', function ($query) {
                            $query->whereType('percentage');
                        });
                    }]);
                })->when($request->tag, function ($query) use ($request) {
                    $query->whereHas('tags', function ($query) use ($request) {
                        $query->where('title', $request->input('tag'));
                    });
                })->where('status', 'active');

            if ($request->orderBy == 'cashback-amount' || $request->orderBy == 'cashback-amount-desc' || $request->orderBy == 'cashback-percentage' || $request->orderBy == 'cashback-percentage-desc' || $request->orderBy == 'cashback-amount-asc' || $request->orderBy == 'cashback-percentage-asc') {
                $stores = $stores->get();
                if ($request->orderBy == 'cashback-amount' || $request->orderBy == 'cashback-amount-desc' || $request->orderBy == 'cashback-percentage' || $request->orderBy == 'cashback-percentage-desc') {
                    $stores = $stores->sortByDesc('cashback_integer')->values();
                }

                if ($request->orderBy == 'cashback-amount-asc' || $request->orderBy == 'cashback-percentage-asc') {
                    $stores = $stores->sortBy('cashback_integer')->values();
                }
            } else {
                $stores = $stores->orderBy('name', 'asc');
            }

            $stores = $stores->paginate(20)->appends(request()->input());
            if ($stores->count() == 0) {
                $data = [
                    'status' => 200,
                    'message' => 'No store found',
                    'data' => []
                ];
                return response()->json($data, 200);
            }
            if ($request->tag === 'afrobot_homepage') {
                $stores = StoreDetailResource::collection($stores);
            } else {
                $stores = StoreResource::collection($stores);
            }
            $data = [
                'status' => 200,
                'message' => 'Success',
                'data' => [
                    'main_banner_image' => getBannerImageUrl($page),
                    'stores' => $stores,
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
            $keywords = $request->keywords;
            if (!empty($keywords[0])) {
                $keywordArray = explode(', ', $keywords);
                $stores = Store::select('id', 'name', 'slug', 'status', 'created_at')->when($request->keywords, function ($query) use ($keywordArray) {
                    $query->whereHas('storeRuleData', function ($query) use ($keywordArray) {
                        $query->where('key', 'meta:keywords')->where(function ($query) use ($keywordArray) {
                            foreach ($keywordArray as $keyword) {
                                $query->orWhereRaw("FIND_IN_SET(?, REPLACE(value, ', ', ','))", [$keyword]);
                            }
                        });
                    });
                })->where('status', 'active')->withCount('cashbacks')->paginate(20)->appends(request()->input());
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
                $stores = Store::select('id', 'name', 'slug', 'status', 'created_at')->where('status', 'active')->withCount('cashbacks')->where(function ($query) use ($links) {
                    foreach ($links as $link) {
                        $query->orWhere('competitors', 'LIKE', '%' . $link . '%');
                    }
                })->orderBy('id')->paginate(12);
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
                'message' => 'Something went wrong, try again',
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

    public function favoriteStores()
    {
        try {
            $cashblackStoreIds = Store::select('id', 'name', 'slug', 'status', 'created_at')
                ->whereStatus('active')
                ->whereHas('categories', function ($query) {
                    $query->where('slug', 'cashblack-to-your-door');
                })->pluck('id');
            $favoriteStores = auth()->user()->favoriteStores()->where('stores.status', 'active')
                ->withCount('cashbacks')
                ->whereNotIn('stores.id', $cashblackStoreIds)
                ->orderBy('favorites.id', 'desc') // Order by the favorites table's created_at column
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
        } catch (Exception $e) {
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
            $store = Store::whereStatus('active')->findOrFail($request->storeId);
            if ($store) {
                auth()->user()->favoriteStores()->where('status', 'active')->syncWithoutDetaching([$store->id]);
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
            $store = Store::whereStatus('active')->findOrFail($request->storeId);
            if ($store) {
                auth()->user()->favoriteStores()->where('status', 'active')->detach($store->id);
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
            $store = Store::where('slug', $slug)
                ->whereStatus('active')
                ->with(['cashbacks' => function ($cashback) {
                    $cashback->orderBy('sale_commission', 'desc');
                }])
                ->withCount('cashbacks')
                ->with(['vouchers' => function ($query) {
                    $query->where('promotion_end_date', '>=', now())->where('status', 'active');
                }])
                ->firstOrFail();
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
        try {
            $page = Page::whereSlug('vouchers')->whereType('system')->whereStatus('active')->pluck('banner_image')->firstOrFail();

            $vouchers = Voucher::when($request->order_by == 'coupons', function ($query) {
                $query->where('promotion_type', 'Coupon');
            })
                ->when($request->order_by == 'offers', function ($query) {
                    $query->where('promotion_type', 'Sale/Discount');
                })
                ->when($request->order_by == 'trending', function ($query) {
                    $query->whereHas('exitClicks')->withCount('exitClicks')->orderBy('exit_clicks_count', 'desc');
                })
                ->when($request->order_by == 'latest', function ($query) {
                    $query->latest();
                })
                ->when($request->order_by == 'expiring', function ($query) {
                    $query->orderBy('promotion_end_date', 'asc');
                })->whereHas('store', function ($query) {
                    $query->where('status', 'active');
                })->with(['store' => function ($query) {
                    $query->select('id', 'name', 'slug', 'status')->where('status', 'active')->withCount('cashbacks');
                }])
                ->where('promotion_end_date', '>=', now())
                ->where('status', 'active')->paginate($request->input('per_page'));

            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => 'success',
                'data' => [
                    'main_banner_image' => getBannerImageUrl($page),
                    'vouchers' => VoucherResource::collection($vouchers),
                    'meta_data' => [
                        "next" => $vouchers->nextPageUrl(),
                        "previous" => $vouchers->previousPageUrl(),
                        "per_page" => $request->per_page,
                        "total" => $vouchers->total(),
                        "current_page" => $vouchers->currentPage(),
                        "total_pages" => $vouchers->lastPage(),
                        "first" => $vouchers->firstItem(),
                        "last" => $vouchers->lastItem()
                    ]
                ],
            ], JsonResponse::HTTP_OK);
        } catch (ModelNotFoundException $ex) { // Vouchers not found
            $data = [
                'status' => 404,
                'message' => 'Store not found',
                'data' => []
            ];
            return response()->json($data, 404);
        } catch (Exception $ex) { // Anything that went wrong
            $data = [
                'status' => 500,
                'message' => 'Something went wrong, try again.' . Log::error($ex->getMessage()),
                'data' => []
            ];
            return response()->json($data, 500);
        }
    }
}
