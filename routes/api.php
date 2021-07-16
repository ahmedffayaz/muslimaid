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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/auth/register', [API\AuthController::class, 'register']);
Route::post('/auth/social', [API\AuthController::class, 'socialLogin']);

Route::post('/auth/login', [API\AuthController::class, 'login']);

Route::post('password/email', [API\AuthController::class, 'forgotPassword']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/me', [API\AuthController::class, 'userData']);
    Route::post('/change_password', [API\AuthController::class, 'changePassword']);

    Route::post('/auth/logout', [API\AuthController::class, 'logout']);
});

Route::apiResource('categories', API\CategoryController::class);
Route::apiResource('stores', API\StoreController::class);
Route::get('featured_cashback',[API\StoreController::class,'featuredCashback']);
Route::get('slider',[API\StoreController::class,'slider']);