<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API as API;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/auth/register', [API\AuthController::class, 'register']);
Route::post('/auth/login', [API\AuthController::class, 'login']);
Route::post('/verify_otp', [API\AuthController::class, 'verifyOtpCode']);
Route::post('/resend_otp', [API\AuthController::class, 'resendOtpCode']);

Route::post('/auth/social', [API\AuthController::class, 'socialLogin']);
Route::post('password/email', [API\AuthController::class, 'forgotPassword']);
Route::post('main_search', [API\HomeController::class, 'mainSearch']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/auth/logout', [API\AuthController::class, 'logout']);
    Route::post('/change_password', [API\AuthController::class, 'changePassword']);

    Route::post('/update_profile', [API\UserController::class, 'updateProfile']);
    Route::post('/update_avatar', [API\UserController::class, 'updateAvatar']);
    Route::get('/user_data', [API\UserController::class, 'userData']);
    Route::get('/user/user-balance', [API\UserController::class, 'userBalance']);
    Route::post('/user/cashback', [API\UserController::class, 'cashback']);
    Route::post('/user/clicks', [API\UserController::class, 'clicks']);
    Route::post('/user/tickets', [API\UserController::class, 'tickets']);
    Route::post('/user/referral-data', [API\UserController::class, 'userReferrals']);
    Route::get('/refer_and_earn', [API\UserController::class, 'referralLink']);
    Route::post('/user/cashouts', [API\UserController::class, 'cashouts']);

    Route::post('/get-fav-stores', [API\StoreController::class, 'favoriteStores']);
    Route::post('/get-fav-cbdoor', [API\StoreController::class, 'favoriteCashbackStores']);
    Route::post('/add-fav-store', [API\StoreController::class, 'addFavoriteStores']);
    Route::post('/remove-fav-store', [API\StoreController::class, 'removeFavoriteStores']);

    Route::post('/dashboard_data', [API\DashboardController::class, 'index']);

    Route::post('/charities', [API\CharityController::class, 'getCharities']);
});

Route::apiResource('stores', API\StoreController::class)->only(['index', 'show']);
Route::post('afrobot', [API\StoreController::class, 'affrobotStores']);

Route::get('vouchers', [API\StoreController::class, 'vouchers']);
Route::get('store-detail/{id}', [API\StoreController::class, 'vouchers']);

Route::post('click/track', [API\ClickController::class, 'track']);

Route::get('home', [API\HomeController::class, 'index']);

Route::get('categories/{letter?}', [API\CategoryController::class, 'index']);
Route::get('child-categories/{slug}/{letter?}', [API\CategoryController::class, 'show']);
Route::get('get-category-stores/{slug}', [API\CategoryController::class, 'getCategoryStores']);
