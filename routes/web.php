<?php

use App\Models\StoreCashback;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Redirect;


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
    ->middleware(['auth','role:admin|data|finance'])
    ->as('admin.')
    ->prefix('admin')
    ->group(function () {

    Route::get('home/{period?}', [App\Http\Controllers\HomeController::class, 'index'])->name('home.index');
    Route::get('/', function(){
        return Redirect::to('admin/home');
    });

    Route::post('dataByPeriod', [App\Http\Controllers\HomeController::class, 'dataByPeriod'])->name('home.index_data');


    //Networks 
    Route::post('networks/fetch',[App\Http\Controllers\Admin\NetworkController::class,'fetch'])->name('networks.fetch');
    Route::get('networks/categories/{network}', [App\Http\Controllers\Admin\NetworkController::class,'categories'])->name('networks.categories');
    Route::get('networks/categories_export/{network}',[App\Http\Controllers\Admin\NetworkController::class,'exportCsv'])->name('networks.categories.export');
    Route::resource('networks', NetworkController::class);

    //Stores 
    Route::get('stores/images/{store}', [App\Http\Controllers\Admin\StoreController::class,'storeImages'])->name('stores.images');
    Route::post('stores/vouchers', [App\Http\Controllers\Admin\StoreController::class,'fetchVouchers'])->name('stores.vouchers');
    Route::post('stores/cashbacks', [App\Http\Controllers\Admin\StoreController::class,'fetchCashbacks'])->name('stores.cashbacks');
    Route::post('stores/reviews', [App\Http\Controllers\Admin\StoreController::class,'fetchReviews'])->name('stores.reviews');
    Route::post('stores/storeimages', [App\Http\Controllers\Admin\StoreController::class,'fetchImages'])->name('stores.fetchimages');
    Route::post('stores/storeaddress', [App\Http\Controllers\Admin\StoreController::class,'fetchAddress'])->name('stores.storeaddress');
    Route::post('stores/fetchseorules', [App\Http\Controllers\Admin\StoreController::class,'fetchSeoRules'])->name('stores.fetchseorules');
    Route::get('stores/reviews/{review}/edit', [App\Http\Controllers\Admin\StoreController::class,'editReview'])->name('stores.reviews.edit');
    Route::get('stores/cashbacks/{cashback}/edit', [App\Http\Controllers\Admin\StoreController::class,'editCashback'])->name('stores.cashbacks.edit');
    Route::post('stores/categories/update', [App\Http\Controllers\Admin\StoreController::class,'updateCategories'])->name('stores.categories.update');
    Route::put('stores/cashbacks/{cashback}/update', [App\Http\Controllers\Admin\StoreController::class,'updateCashback'])->name('stores.cashbacks.update');
    Route::post('stores/cashbacks/save', [App\Http\Controllers\Admin\StoreController::class,'createCashback'])->name('stores.cashbacks.store');
    Route::post('stores/images/upload/{store}', [App\Http\Controllers\Admin\StoreController::class,'uploadImage'])->name('stores.images.upload');
    Route::get('stores/images/delete/{storeimage}', [App\Http\Controllers\Admin\StoreController::class,'deleteImage'])->name('stores.images.delete');
    Route::get('stores/export', [App\Http\Controllers\Admin\StoreController::class,'exportCsv'])->name('stores.export');
    Route::post('stores/fetch',[App\Http\Controllers\Admin\StoreController::class,'fetch'])->name('stores.fetch');
    Route::post('stores/search_stores',  [App\Http\Controllers\Admin\StoreController::class,'searchStores'])->name('stores.search_stores');
    Route::get('stores/editor_picks', [App\Http\Controllers\Admin\StoreController::class,'editorPicks'])->name('stores.editor_picks');
    Route::post('stores/create_editor_picks',[App\Http\Controllers\Admin\StoreController::class,'createEditorPick'])->name('stores.create_editor_picks');
    Route::post('stores/fetch_editor_picks',[App\Http\Controllers\Admin\StoreController::class,'fetchEditorPicks'])->name('stores.fetch_editor_picks');
    Route::post('stores/search_editor_picks',  [App\Http\Controllers\Admin\StoreController::class,'searchEditorPicks'])->name('stores.search_editor_picks');
    Route::put('stores/override_categories/{store}/update', [App\Http\Controllers\Admin\StoreController::class,'overrideCategories'])->name('stores.override_categories');
    Route::put('stores/override_cashback/{store}/update', [App\Http\Controllers\Admin\StoreController::class,'overrideCashback'])->name('stores.override_cashback');
    Route::get('stores/show', [App\Http\Controllers\Admin\StoreController::class,'showStore'])->name('stores.show_store');
    Route::post('stores/store_seo_rule',  [App\Http\Controllers\Admin\StoreController::class,'storeSeoRule'])->name('stores.save_seo_rule');
    Route::post('stores/store_address',  [App\Http\Controllers\Admin\StoreController::class,'addStoreAddress'])->name('stores.save_address');
    Route::get('stores/seo/{id}',  [App\Http\Controllers\Admin\StoreController::class,'editStoreSeoRule'])->name('stores.edit_seo');
    Route::put('stores/seo/update',  [App\Http\Controllers\Admin\StoreController::class,'updateStoreSeoRule'])->name('stores.update_seo');
    Route::get('stores/seo/delete/{id}', [App\Http\Controllers\Admin\StoreController::class,'deleteStoreSeoRule'])->name('stores.seo.delete');
    Route::get('stores/address/{id}',  [App\Http\Controllers\Admin\StoreController::class,'editStoreAddress'])->name('stores.edit_address');
    Route::put('stores/address/update',  [App\Http\Controllers\Admin\StoreController::class,'updateStoreAddress'])->name('stores.update_address');
    Route::get('stores/address/delete/{id}', [App\Http\Controllers\Admin\StoreController::class,'deleteStoreAddress'])->name('stores.address.delete');
    Route::resource('stores', StoreController::class);
    Route::resource('storecashbacks', StoreCashbackController::class);

    //Vouchers 
    Route::resource('vouchers', VouchersController::class);
    Route::post('vouchers/fetch',[App\Http\Controllers\Admin\VouchersController::class,'fetch'])->name('vouchers.fetch');
    Route::get('voucherss/export', [App\Http\Controllers\Admin\VouchersController::class,'exportCsv'])->name('vouchers.export');
    Route::post('vouchers/search_vouchers',  [App\Http\Controllers\Admin\VouchersController::class,'searchVouchers'])->name('vouchers.search_vouchers');

    //Categoires
    Route::get('categories/export', [App\Http\Controllers\Admin\CategoryController::class,'exportCsv'])->name('categories.export');
    Route::get('categories/picks/{category}', [App\Http\Controllers\Admin\CategoryController::class,'picks'])->name('categories.picks');
    Route::post('categories/fetch',[App\Http\Controllers\Admin\CategoryController::class,'fetch'])->name('categories.fetch');
    Route::post('categories/search_categories',  [App\Http\Controllers\Admin\CategoryController::class,'searcCategories'])->name('categories.search_categories');
    Route::resource('categories', CategoryController::class);
    
    //IMported Network Categories 
    Route::post('importedcategories/fetch',[App\Http\Controllers\Admin\ImportedCategoryController::class,'fetch'])->name('importedcategories.fetch');
    Route::post('importedcategories/search_importedcategories',  [App\Http\Controllers\Admin\ImportedCategoryController::class,'searcImportedCategories'])->name('importedcategories.search_importedcategories');
    Route::resource('importedcategories', ImportedCategoryController::class);
    
    //Users 
    Route::post('users/cashbacks', [App\Http\Controllers\Admin\UserController::class,'fetchCashbacks'])->name('users.cashbacks');
    Route::post('users/clicks', [App\Http\Controllers\Admin\UserController::class,'fetchClicks'])->name('users.clicks');
    Route::get('users/export', [App\Http\Controllers\Admin\UserController::class,'exportCsv'])->name('users.export');
    Route::get('users/paymentinfo/{user}', [App\Http\Controllers\Admin\UserController::class,'paymentInfo'])->name('users.paymentinfo');
    Route::get('users/password/{user}', [App\Http\Controllers\Admin\UserController::class,'changePassword'])->name('users.password');
    Route::put('users/passwordsave/{user}', [App\Http\Controllers\Admin\UserController::class,'savePassword'])->name('users.save_password');
    Route::post('users/paymentsave', [App\Http\Controllers\Admin\UserController::class,'paymentSave'])->name('users.payment_save');
    Route::post('users/fetch',[App\Http\Controllers\Admin\UserController::class,'fetch'])->name('users.fetch');
    Route::post('users/search_users',  [App\Http\Controllers\Admin\UserController::class,'searchUsers'])->name('users.search_users');
    Route::get('users/show', [App\Http\Controllers\Admin\UserController::class,'showUser'])->name('users.show_user');
    Route::resource('users', UserController::class);
    
    // Imorters
    Route::post('importer/import', [App\Http\Controllers\Admin\ImporterController::class,'import'])->name('importer.import');
    Route::get('importer/commissions', [App\Http\Controllers\Admin\ImporterController::class,'import_commissions'])->name('importer.commissions');
    Route::get('importer/vouchers', [App\Http\Controllers\Admin\ImporterController::class,'import_coupons'])->name('importer.vouchers');
    Route::post('importer/save_settings', [App\Http\Controllers\Admin\ImporterController::class,'saveSettings'])->name('importer.save_settings');
    Route::get('importer/importer_setting_form/{id}', [App\Http\Controllers\Admin\ImporterController::class,'importerSettingForm'])->name('importer.importer_setting_form');
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
    Route::get('commissions/status_history/{commission}',  [App\Http\Controllers\Admin\CommissionController::class,'statusHistory'])->name('commissions.history');
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
    Route::get('mailer_settings', [App\Http\Controllers\Admin\SettingsController::class,'mailerSettings'])->name('settings.mailer_settings');
    Route::get('cashback_status', [App\Http\Controllers\Admin\SettingsController::class,'cashbackStatusNames'])->name('settings.cashback_status');
    Route::get('permissions', [App\Http\Controllers\Admin\SettingsController::class,'permissions'])->name('settings.permissions');
    Route::get('menu', [App\Http\Controllers\Admin\SettingsController::class,'menu'])->name('settings.menu');
    Route::post('settings/mailer_settings_save', [App\Http\Controllers\Admin\SettingsController::class,'saveMailerSettings'])->name('settings.mailer_settings_save');
    Route::post('settings/cashback_statuses_save', [App\Http\Controllers\Admin\SettingsController::class,'saveCashbackStatuses'])->name('settings.cashback_statuses_save');
    Route::post('settings/update_permissions', [App\Http\Controllers\Admin\SettingsController::class,'updatePermissions'])->name('settings.update_permissions');
    Route::post('settings/settings_save', [App\Http\Controllers\Admin\SettingsController::class,'saveSettings'])->name('settings.settings_save');
    Route::post('settings/fetch',[App\Http\Controllers\Admin\SettingsController::class,'fetch'])->name('settings.fetch');
    Route::post('settings/search_settings',  [App\Http\Controllers\Admin\SettingsController::class,'searchSettings'])->name('settings.search_settings');
    Route::post('maintenance', [App\Http\Controllers\Admin\SettingsController::class,'maintenance'])->name('settings.maintenance');
    Route::resource('settings', SettingsController::class);

    Route::get('manage-menus/{id?}',[App\Http\Controllers\Admin\MenuController::class,'index']);
    Route::post('create-menu',[App\Http\Controllers\Admin\MenuController::class,'store']);
    Route::get('add-categories-to-menu',[App\Http\Controllers\Admin\MenuController::class,'addCatToMenu']);
    Route::get('add-post-to-menu',[App\Http\Controllers\Admin\MenuController::class,'addPostToMenu']);
    Route::get('add-custom-link',[App\Http\Controllers\Admin\MenuController::class,'addCustomLink']);	
    Route::get('update-menu',[App\Http\Controllers\Admin\MenuController::class,'updateMenu']);			
    
    // Languages 
    Route::post('languages/fetch',[App\Http\Controllers\Admin\LanguageController::class,'fetch'])->name('languages.fetch');
    Route::post('languages/search_languages',  [App\Http\Controllers\Admin\LanguageController::class,'searchLanguages'])->name('languages.search_languages');

    Route::resource('languages', LanguageController::class);

    Route::post('translations/fetch',[App\Http\Controllers\Admin\Translations\TranslationController::class,'fetch'])->name('translations.fetch');
    Route::post('translations/search_translations',  [App\Http\Controllers\Admin\Translations\TranslationController::class,'searchTranslations'])->name('translations.search_translations');

    Route::resource('translations', Translations\TranslationController::class);
    Route::resource('lines', Translations\LanguageLineController::class);

    // Route::get('get/user/{id}', [App\Http\Controllers\Admin\TestimonialController::class,'userDetails'])->name('users.fetch');

    Route::post('tickets/fetch',[App\Http\Controllers\Admin\TicketsController::class,'fetch'])->name('tickets.fetch');
    Route::post('tickets/search',  [App\Http\Controllers\Admin\TicketsController::class,'searchTickets'])->name('tickets.search');
    Route::put('tickets/close_ticket/{ticket}',[App\Http\Controllers\Admin\TicketsController::class,'closeTicket'])->name('tickets.close');
    Route::resource('tickets',  TicketsController::class);
    Route::resource('ticketCategory',TicketCategoryController::class);
    
    Route::resource('replies', RepliesController::class);


    Route::put('profile/passwordsave/{profile}', [App\Http\Controllers\Admin\ProfileController::class,'savePassword'])->name('profile.save_password');
    Route::resource('profile', ProfileController::class);

    Route::post('sliders/sort_slides',[App\Http\Controllers\Admin\SliderController::class, 'sortSlides'])->name('sort_slides');
    Route::resource('sliders', SliderController::class);
    Route::resource('slides', SlidesController::class);
    Route::resource('pages', PagesController::class);
    Route::resource('blogs', BlogController::class);
    Route::resource('testimonials', TestimonialController::class);
    Route::resource('seo', SeoController::class);
    Route::resource('email_templates', EmailTemplatesController::class);

    
    
    Route::get('site/shutdown', function(){
        return Artisan::call('down');
    });
    
    Route::get('site/live', function(){
        return Artisan::call('up');
    });



});

//Front Website Routes

Route::get('new_ticket', [App\Http\Controllers\Frontend\TicketsController::class, 'create']);
Route::post('new_ticket',[App\Http\Controllers\Frontend\TicketsController::class, 'store']);

Route::get('offers',[App\Http\Controllers\Frontend\PagesController::class, 'offers'])->name('offers');
Route::get('vouchers',[App\Http\Controllers\Frontend\PagesController::class, 'vouchers'])->name('vouchers');
Route::get('about',[App\Http\Controllers\Frontend\PagesController::class, 'about'])->name('about');
Route::get('contact',[App\Http\Controllers\Frontend\PagesController::class, 'contact'])->name('contact');
Route::get('blog',[App\Http\Controllers\Frontend\PagesController::class, 'blog'])->name('blog');
Route::get('search',[App\Http\Controllers\Frontend\PagesController::class, 'search'])->name('search');
Route::get('category/{slug}',[App\Http\Controllers\Frontend\PagesController::class, 'cashbackByCategory'])->name('cashabck');
Route::get('top-cashback',[App\Http\Controllers\Frontend\PagesController::class, 'topStores'])->name('top_stores');
Route::get('trending',[App\Http\Controllers\Frontend\PagesController::class, 'trending'])->name('trending');
Route::get('cashback/{slug}',[App\Http\Controllers\Frontend\StoreController::class, 'show'])->name('store.show');
Route::get('categories/cashback-to-door',[App\Http\Controllers\Frontend\StoreController::class, 'storeLocation'])->name('store.location');
Route::get('search_suggestions',[App\Http\Controllers\Frontend\PagesController::class, 'searchSuggestions'])->name('search_suggestions');
Route::get('pages/{page}',[App\Http\Controllers\Frontend\PagesController::class, 'show'])->name('page');
Route::get('post/{blog}',[App\Http\Controllers\Frontend\PagesController::class, 'blogPost'])->name('post');
Route::post('contact_form',[App\Http\Controllers\Frontend\PagesController::class, 'contactForm'])->name('contactForm');
Route::get('all_stores/{letter}',[App\Http\Controllers\Frontend\PagesController::class, 'allStoresLetter'])->name('all_stores_of_letter');
Route::get('all_stores',[App\Http\Controllers\Frontend\PagesController::class, 'allStores'])->name('all_stores');

Route::resource('newsletter',App\Http\Controllers\Frontend\NewsletterController::class);
Route::resource('categories',App\Http\Controllers\Frontend\CategoryController::class);



//CLient Dashboard routes
Route::namespace('App\Http\Controllers\Client')
    ->middleware(['auth','role:user'])
    ->as('account.')
    ->prefix('account')
    ->group(function () {
        Route::get('dashboard',[App\Http\Controllers\Client\DashboardController::class, 'index'])->name('dashboard');
        Route::get('profile',[App\Http\Controllers\Client\DashboardController::class, 'edit'])->name('profile');
        Route::put('profile/update/{user}',[App\Http\Controllers\Client\DashboardController::class, 'update'])->name('profile.update');
        Route::get('cashback',[App\Http\Controllers\Client\DashboardController::class, 'cashback'])->name('cashback');
        Route::get('clicks',[App\Http\Controllers\Client\DashboardController::class, 'clicks'])->name('clicks');
        Route::get('change_password',[App\Http\Controllers\Client\DashboardController::class, 'changePassword'])->name('change_password');
        Route::post('users/passwordsave/', [App\Http\Controllers\Client\DashboardController::class,'savePassword'])->name('save_password');
        Route::resource('withdraw',PaymentController::class);
        Route::get('statement',[App\Http\Controllers\Client\PaymentController::class,'statement'])->name('statement');
        Route::get('payment-details',[App\Http\Controllers\Client\PaymentController::class,'paymentDetails'])->name('payment_details');
        Route::post('payment-save',[App\Http\Controllers\Client\PaymentController::class,'paymentSave'])->name('payment_save');
        Route::post('cashout',[App\Http\Controllers\Client\PaymentController::class,'cashout'])->name('cashout');
        Route::post('claim/step2',[App\Http\Controllers\Client\ClaimController::class,'step2'])->name('claim.step2');
        Route::post('claim/step3',[App\Http\Controllers\Client\ClaimController::class,'step3'])->name('claim.step3');
        Route::resource('claim', ClaimController::class);
        Route::resource('referral',ReferController::class);
        Route::post('send-referral-link',[App\Http\Controllers\Client\ReferController::class, 'sendReferralLink'])->name('send-referral-link');
        
    });


    Route::group(['prefix' => 'filemanager', 'middleware' => ['web', 'auth']], function () {
        \UniSharp\LaravelFilemanager\Lfm::routes();
    });

    Route::get('login/{provider}', [App\Http\Controllers\SocialController::class, 'redirect']);
    Route::get('login/{provider}/callback',[App\Http\Controllers\SocialController::class, 'Callback']);
    Route::get('register-form',[App\Http\Controllers\Auth\registerController::class,'showRegistrationForm'])->name('register-form');

   
     Route::get('account/verify/{token}', [App\Http\Controllers\Auth\VerifyController::class, 'verifyAccount'])->name('user.verify'); 