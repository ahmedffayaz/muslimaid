<?php

use Illuminate\Support\Facades\Route;
use App\Models\StoreCashback;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $cashbacks = StoreCashback::latest()->get();

    return view('welcome', ['cashbacks' => $cashbacks]);
  
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



//Admin routes
Route::namespace('App\Http\Controllers\Admin')
    ->middleware(['auth'])
    ->as('admin.')
    ->prefix('admin')
    ->group(function () {

    Route::resource('networks', NetworkController::class);
    Route::resource('stores', StoreController::class);
    Route::resource('storecashbacks', StoreCashbackController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('users', UserController::class);
    Route::get('importer/import', [App\Http\Controllers\Admin\ImporterController::class,'import'])->name('importer.import');
    Route::resource('importer', ImporterController::class);



});

