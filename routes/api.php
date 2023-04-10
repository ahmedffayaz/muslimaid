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

Route::post('/auth/social', [API\AuthController::class, 'socialLogin']);
Route::post('password/email', [API\AuthController::class, 'forgotPassword']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/auth/logout', [API\AuthController::class, 'logout']);
    Route::post('/change_password', [API\AuthController::class, 'changePassword']);
    Route::post('/update_profile',[API\AuthController::class, 'updateProfile'])->name('update_profile');
    Route::get('/user_data', [API\AuthController::class, 'userData'])->name('user_data');

    Route::get('/user/cashback',[API\DashboardController::class, 'cashback'])->name('cashback');
    Route::get('/user/clicks',[API\DashboardController::class, 'clicks'])->name('clicks');
    Route::get('/user/payment_methods',[API\PaymentController::class, 'paymentMethods']);
    Route::post('/user/payment_methods_save',[API\PaymentController::class, 'paymentSave'])->name('payment_save');
    Route::get('/user/cashouts',[API\PaymentController::class, 'cashouts']);
    Route::post('/user/withdraw',[API\PaymentController::class, 'withdraw']);
    Route::get('/user/claims',[API\ClaimController::class, 'claims']);
    Route::get('/user/show-retailers',[API\ClaimController::class, 'step1']);
    Route::post('/user/claim-step-2',[API\ClaimController::class, 'step2']);
    Route::post('/user/claim-step-3',[API\ClaimController::class, 'step3']);
    Route::get('/user/user-balance',[API\DashboardController::class, 'userBalance']);

});

Route::apiResource('stores', API\StoreController::class)->only(['index', 'show']);
Route::get('featured_cashback',[API\StoreController::class,'featuredCashback']);
Route::get('slider',[API\StoreController::class,'slider']);
Route::get('vouchers',[API\StoreController::class,'vouchers']);
Route::post('click/track', [API\ClickController::class, 'track']);
Route::get('home', [API\HomeController::class, 'index']);
Route::get('categories/{letter?}', [API\CategoryController::class, 'index']);
Route::get('child-categories/{slug}/{letter?}', [API\CategoryController::class, 'show']);
