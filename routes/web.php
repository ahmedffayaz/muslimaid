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
Route::resource('/', App\Http\Controllers\Frontend\HomeController::class);
Route::get('/set-locale/{locale}', [App\Http\Controllers\Frontend\HomeController::class, 'setLocale']);

Auth::routes();

Route::namespace('App\Http\Controllers\Website')->prefix('site')->name('site.')->group(function () {

    Route::resource('/exit_click', ClickController::class);

});


//Admin routes
Route::namespace('App\Http\Controllers\Admin')
    ->middleware(['auth','role:admin'])
    ->as('admin.')
    ->prefix('admin')
    ->group(function () {

    Route::get('home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    //Networks 
    Route::post('networks/fetch',[App\Http\Controllers\Admin\NetworkController::class,'fetch'])->name('networks.fetch');
    Route::get('networks/categories/{network}', [App\Http\Controllers\Admin\NetworkController::class,'categories'])->name('networks.categories');
    Route::get('networks/categories_export/{network}',[App\Http\Controllers\Admin\NetworkController::class,'exportCsv'])->name('networks.categories.export');
    Route::resource('networks', NetworkController::class);

    //Stores 
    Route::get('stores/images/{store}', [App\Http\Controllers\Admin\StoreController::class,'storeImages'])->name('stores.images');
    Route::post('stores/images/upload/{store}', [App\Http\Controllers\Admin\StoreController::class,'uploadImage'])->name('stores.images.upload');
    Route::get('stores/images/delete/{storeimage}', [App\Http\Controllers\Admin\StoreController::class,'deleteImage'])->name('stores.images.delete');
    Route::get('stores/export', [App\Http\Controllers\Admin\StoreController::class,'exportCsv'])->name('stores.export');
    Route::post('stores/fetch',[App\Http\Controllers\Admin\StoreController::class,'fetch'])->name('stores.fetch');
    Route::post('stores/search_stores',  [App\Http\Controllers\Admin\StoreController::class,'searchStores'])->name('stores.search_stores');
    Route::resource('stores', StoreController::class);
    Route::resource('storecashbacks', StoreCashbackController::class);

    //Vouchers 
    Route::resource('vouchers', VouchersController::class);
    Route::post('vouchers/fetch',[App\Http\Controllers\Admin\VouchersController::class,'fetch'])->name('vouchers.fetch');
    Route::get('voucherss/export', [App\Http\Controllers\Admin\VouchersController::class,'exportCsv'])->name('vouchers.export');
    Route::post('vouchers/search_vouchers',  [App\Http\Controllers\Admin\VouchersController::class,'searchVouchers'])->name('vouchers.search_vouchers');

    //Categoires
    Route::get('categories/export', [App\Http\Controllers\Admin\CategoryController::class,'exportCsv'])->name('categories.export');
    Route::post('categories/fetch',[App\Http\Controllers\Admin\CategoryController::class,'fetch'])->name('categories.fetch');
    Route::post('categories/search_categories',  [App\Http\Controllers\Admin\CategoryController::class,'searcCategories'])->name('categories.search_categories');
    Route::resource('categories', CategoryController::class);
    
    //IMported Network Categories 
    Route::post('importedcategories/fetch',[App\Http\Controllers\Admin\ ImportedCategoryController::class,'fetch'])->name('importedcategories.fetch');
    Route::post('importedcategories/search_importedcategories',  [App\Http\Controllers\Admin\ImportedCategoryController::class,'searcImportedCategories'])->name('importedcategories.search_importedcategories');
    Route::resource('importedcategories', ImportedCategoryController::class);
    
    //Users 
    Route::get('users/export', [App\Http\Controllers\Admin\UserController::class,'exportCsv'])->name('users.export');
    Route::get('users/paymentinfo/{user}', [App\Http\Controllers\Admin\UserController::class,'paymentInfo'])->name('users.paymentinfo');
    Route::get('users/password/{user}', [App\Http\Controllers\Admin\UserController::class,'changePassword'])->name('users.password');
    Route::put('users/passwordsave/{user}', [App\Http\Controllers\Admin\UserController::class,'savePassword'])->name('users.save_password');
    Route::post('users/paymentsave', [App\Http\Controllers\Admin\UserController::class,'paymentSave'])->name('users.payment_save');
    Route::post('users/fetch',[App\Http\Controllers\Admin\UserController::class,'fetch'])->name('users.fetch');
    Route::post('users/search_users',  [App\Http\Controllers\Admin\UserController::class,'searchUsers'])->name('users.search_users');
    Route::resource('users', UserController::class);
    
    // Imorters
    Route::post('importer/import', [App\Http\Controllers\Admin\ImporterController::class,'import'])->name('importer.import');
    Route::get('importer/commissions', [App\Http\Controllers\Admin\ImporterController::class,'import_commissions'])->name('importer.commissions');
    Route::get('importer/vouchers', [App\Http\Controllers\Admin\ImporterController::class,'import_coupons'])->name('importer.vouchers');
    Route::post('importer/save_settings', [App\Http\Controllers\Admin\ImporterController::class,'saveSettings'])->name('importer.save_settings');
    Route::resource('importer', ImporterController::class);

    //Clicks 
    Route::get('clicks/export', [App\Http\Controllers\Admin\ClickController::class,'exportCsv'])->name('clicks.export');
    Route::post('clicks/fetch',[App\Http\Controllers\Admin\ClickController::class,'fetch'])->name('clicks.fetch');
    Route::post('clicks/search_clicks',  [App\Http\Controllers\Admin\ClickController::class,'searchClicks'])->name('clicks.search_clicks');
    Route::resource('clicks', ClickController::class);
    
    // Cashbacks
    Route::get('commissionss/export', [App\Http\Controllers\Admin\CommissionController::class,'exportCsv'])->name('commissions.export');
    Route::post('commissions/fetch',[App\Http\Controllers\Admin\CommissionController::class,'fetch'])->name('commissions.fetch');
    Route::get('addmultiple/commissions',[App\Http\Controllers\Admin\CommissionController::class,'createMultiple'])->name('commissions.create_multiple');
    Route::post('commissions/storemultiple',[App\Http\Controllers\Admin\CommissionController::class,'storeMultiple'])->name('commissions.store_multiple');
    Route::post('commissions/search_commissions',  [App\Http\Controllers\Admin\CommissionController::class,'searchCommissions'])->name('commissions.search_commissions');
    Route::resource('commissions', CommissionController::class);
    Route::resource('cashouts', CashoutController::class);
    
    // Reviews
    Route::resource('reviews', StoreReviewsController::class);
    Route::post('reviews/fetch',[App\Http\Controllers\Admin\StoreReviewsController::class,'fetch'])->name('reviews.fetch');
    Route::get('reviews/export', [App\Http\Controllers\Admin\StoreReviewsController::class,'exportCsv'])->name('reviews.export');
    Route::post('reviews/search_reviews',  [App\Http\Controllers\Admin\StoreReviewsController::class,'searchReviews'])->name('reviews.search_reviews');

    // Reports
    Route::get('reports/store_performance',  [App\Http\Controllers\Admin\ReportsController::class,'store_performance'])->name('reports.performance');
    Route::post('reports/search_performance',  [App\Http\Controllers\Admin\ReportsController::class,'search_performance'])->name('reports.search_performance');
    Route::post('reports/performance/fetch',[App\Http\Controllers\Admin\ReportsController::class,'fetchPerformance'])->name('reports.fetch_performance');
    Route::get('reports/earnings',  [App\Http\Controllers\Admin\ReportsController::class,'earnings'])->name('reports.earnings');
    Route::post('reports/search_earnings',  [App\Http\Controllers\Admin\ReportsController::class,'search_earnings'])->name('reports.search_earnings');
    Route::post('reports/earnings/fetch',[App\Http\Controllers\Admin\ReportsController::class,'fetchEarnings'])->name('reports.fetch_earnings');

    //Settings
    Route::get('settings/export', [App\Http\Controllers\Admin\SettingsController::class,'exportCsv'])->name('settings.export');
    Route::post('settings/fetch',[App\Http\Controllers\Admin\SettingsController::class,'fetch'])->name('settings.fetch');
    Route::post('settings/search_settings',  [App\Http\Controllers\Admin\SettingsController::class,'searchSettings'])->name('settings.search_settings');
    Route::resource('settings', SettingsController::class);
    
    // Languages 
    Route::post('languages/fetch',[App\Http\Controllers\Admin\LanguageController::class,'fetch'])->name('languages.fetch');
    Route::post('languages/search_languages',  [App\Http\Controllers\Admin\LanguageController::class,'searchLanguages'])->name('languages.search_languages');

    Route::resource('languages', LanguageController::class);

    Route::post('translations/fetch',[App\Http\Controllers\Admin\Translations\TranslationController::class,'fetch'])->name('translations.fetch');
    Route::post('translations/search_translations',  [App\Http\Controllers\Admin\Translations\TranslationController::class,'searchTranslations'])->name('translations.search_translations');

    Route::resource('translations', Translations\TranslationController::class);
    Route::resource('lines', Translations\LanguageLineController::class);



});

